<?php

namespace App\Services;

use App\Models\PointHistory;
use App\Models\PointLedger;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PointService
{

    private function cacheKey(User $user) {
        return "user_points:{$user->id}";
    }
    public function getTotalPoints(User $user) {
        $cacheKey = $this->cacheKey($user);
        return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($user) {
            return PointLedger::query()
                ->where('user_id', $user->id)
                ->where('balance', '>', 0)
                ->where('expires_at', '>', now())
                ->sum('balance');
        });
    }

    public function earnPoints(User $user, int $amount, ?string $description=null) {
        DB::transaction(function () use ($user, $amount, $description) {
            $expiresAt = now()->addQuarter()->lastOfQuarter()->endOfDay();

            PointLedger::query()->create([
                'user_id' => $user->id,
                'amount' => $amount,
                'used_amount' => 0,
                'balance' => $amount,
                'expires_at' => $expiresAt,
            ]);

            PointHistory::query()->create([
                'user_id' => $user->id,
                'type' => 'earned',
                'amount' => $amount,
                'description' => $description,
            ]);
        });
        $cacheKey = $this->cacheKey($user);
        Cache::forget($cacheKey);
    }

    public function redeemPoints(User $user, int $amountToRedeemed, ?string $description=null) {
        if ($this->getTotalPoints($user) < $amountToRedeemed) {
            throw new \Exception("You don't have enough points to redeem this point.");
        }
        DB::transaction(function () use ($user, $amountToRedeemed, $description) {
            $ledgers = PointLedger::query()
                ->where('user_id', $user->id)
                ->where('balance', '>', 0)
                ->where('expires_at', '>', now())
                ->orderBy('expires_at')
                ->orderBy('balance')
                ->lockForUpdate()
                ->get();

            $amount = $amountToRedeemed;

            foreach ($ledgers as $ledger) {
                if ($amount <= 0) break;
                if ($ledger->balance >= $amount) {
                    $ledger->balance -= $amount;
                    $ledger->used_amount += $amount;
                    $ledger->save();
                    $amount = 0;
                } else {
                    $amount -= $ledger->balance;
                    $ledger->used_amount = $ledger->amount;
                    $ledger->balance = 0;
                    $ledger->save();
                }
            }

            PointHistory::query()->create([
                'user_id' => $user->id,
                'type' => 'redeemed',
                'amount' => $amountToRedeemed,
                'description' => $description,
            ]);
        });

        Cache::forget($this->cacheKey($user));
    }
    public function getPointsByQuarters(User $user)
    {
        $cacheKey = "user_points_by_quarter:{$user->id}";

        return Cache::remember($cacheKey, 60 * 60 * 24, function () use ($user) {

            $ledgers = PointLedger::query()
                ->select(
                    DB::raw('DATE(expires_at) as expire_date'),
                    DB::raw('SUM(balance) as total_points')
                )
                ->where('user_id', $user->id)
                ->where('balance', '>', 0)
                ->where('expires_at', '>', now())
                ->groupBy('expire_date')
                ->orderBy('expire_date')
                ->get();

            return $ledgers->map(function ($ledger) {
                $date = Carbon::parse($ledger->expire_date);

                return [
                    'quarter_label' => "Q{$date->quarter} {$date->year}",
                    'expire_date'   => $date->format('Y-m-d'),
                    'points'        => (int) $ledger->total_points,
                ];
            });
        });
    }
}
