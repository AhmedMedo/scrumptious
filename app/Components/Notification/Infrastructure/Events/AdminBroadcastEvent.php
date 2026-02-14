<?php

namespace App\Components\Notification\Infrastructure\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminBroadcastEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $broadcastUuid,
        public string $title,
        public string $body,
        public array $userUuids,
        public ?array $data = null
    ) {
    }
}
