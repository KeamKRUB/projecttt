<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Broadcast;

class TestBroadcast extends Command
{
    protected $signature = 'app:broadcast
                            {--channel=}
                            {--event=}
                            {--data=}';

    protected $description = 'Test broadcast';

    public function handle()
    {
        $data = json_decode($this->option('data'), true);

        Broadcast::on($this->option('channel'))
            ->as($this->option('event'))
            ->with($data)
            ->sendNow();

        $this->info('Broadcast sent');
    }
}
