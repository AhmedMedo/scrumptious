<?php

namespace App\Components\Notification\Infrastructure\Console\Commands;

use App\Components\Notification\Infrastructure\Jobs\ProcessTargetRemindersJob;
use Illuminate\Console\Command;

class SendTargetRemindersCommand extends Command
{
    protected $signature = 'notifications:send-target-reminders';

    protected $description = 'Send reminders for targets that are ending soon';

    public function handle(): int
    {
        $this->info('Processing target reminders...');

        ProcessTargetRemindersJob::dispatch();

        $this->info('Target reminders job dispatched successfully.');

        return self::SUCCESS;
    }
}
