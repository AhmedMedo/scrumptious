<?php

namespace App\Components\Notification\Infrastructure\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TargetReminderEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $userUuid,
        public string $targetUuid,
        public string $targetTitle,
        public string $endDate,
        public int $daysRemaining
    ) {
    }
}
