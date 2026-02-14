<?php

namespace App\Components\Notification\Infrastructure\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MealPlanCustomizedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $userUuid,
        public string $planUuid,
        public string $planName,
        public array $customizationDetails = []
    ) {
    }
}
