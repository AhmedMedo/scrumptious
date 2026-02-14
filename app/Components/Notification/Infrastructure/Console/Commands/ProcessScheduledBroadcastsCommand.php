<?php

namespace App\Components\Notification\Infrastructure\Console\Commands;

use App\Components\Notification\Infrastructure\Jobs\ProcessScheduledBroadcastsJob;
use Illuminate\Console\Command;

class ProcessScheduledBroadcastsCommand extends Command
{
    protected $signature = 'notifications:process-scheduled-broadcasts';

    protected $description = 'Process and send scheduled broadcast notifications';

    public function handle(): int
    {
        $this->info('Processing scheduled broadcasts...');

        ProcessScheduledBroadcastsJob::dispatch();

        $this->info('Scheduled broadcasts job dispatched successfully.');

        return self::SUCCESS;
    }
}
