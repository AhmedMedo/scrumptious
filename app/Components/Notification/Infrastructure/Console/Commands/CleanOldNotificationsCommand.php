<?php

namespace App\Components\Notification\Infrastructure\Console\Commands;

use App\Components\Notification\Data\Entity\NotificationEntity;
use Illuminate\Console\Command;

class CleanOldNotificationsCommand extends Command
{
    protected $signature = 'notifications:clean-old {--days=90 : Number of days to retain notifications}';

    protected $description = 'Clean up old notifications from the database';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Cleaning notifications older than {$days} days (before {$cutoffDate->toDateString()})...");

        $count = NotificationEntity::where('created_at', '<', $cutoffDate)
            ->where('is_read', true)
            ->delete();

        $this->info("Deleted {$count} old notifications.");

        return self::SUCCESS;
    }
}
