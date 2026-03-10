<?php

namespace App\Jobs;

use App\Mail\PointEarned;
use App\Mail\PointRedeemed;
use App\Models\User;
use App\Services\PointService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendPointNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public string $type,
        public int $amount,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pointService = new PointService();

        if ($this->type === 'earned') {

            Mail::to($this->user->email)
                ->send(
                    new PointEarned(
                        $this->amount,
                        $pointService->getTotalPoints($this->user)
                    )
                );

        } elseif ($this->type === 'redeemed') {

            Mail::to($this->user->email)
                ->send(
                    new PointRedeemed(
                        $this->amount,
                    )
                );

        } elseif ($this->type === 'expired') {

            $subject = "แต้มของคุณจำนวน {$this->amount} หมดอายุแล้ว";

        }
    }
}
