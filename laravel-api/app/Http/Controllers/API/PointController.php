<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\SendPointNotification;
use App\Models\PointHistory;
use App\Models\PointLedger;
use App\Services\PointService;
use Illuminate\Http\Request;

class PointController extends Controller
{

    public function index(Request $request) {
        $user = $request->user();
        return response()->json([
            'ledgers' => PointLedger::query()
                ->where('user_id', $user->id)
                ->orderBy('expires_at')
                ->orderBy('balance')
                ->get(),
            'histories' => PointHistory::query()->where('user_id', $user->id)->oldest()->get(),
        ]);
    }
    public function show(Request $request, PointService $pointService) {
        $user = $request->user();
        return response()->json([
            'total_points' => $pointService->getTotalPoints($user),
        ]);
    }

    public function earn(Request $request, PointService $pointService) {
        $user = $request->user();
        $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);
        $pointService->earnPoints($user, $request->amount, $request->description);
        SendPointNotification::dispatch($user, 'earned', $request->amount);
        return response()->json([
            'success' => true,
            'total_points' => $pointService->getTotalPoints($user),
        ]);
    }

    public function redeem(Request $request, PointService $pointService) {
        $user = $request->user();
        $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);
        try {
            $pointService->redeemPoints($user, $request->amount, $request->description);
            SendPointNotification::dispatch($user, 'redeemed', $request->amount);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
        return response()->json([
            'success' => true,
            'total_points' => $pointService->getTotalPoints($user),
        ]);
    }
}
