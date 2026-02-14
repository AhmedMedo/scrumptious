<?php

namespace App\Components\Notification\Infrastructure\Listeners;

use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;
use App\Components\Notification\Infrastructure\Events\MealPlanCustomizedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendMealPlanCustomizedNotification implements ShouldQueue
{
    public function __construct(
        private readonly NotificationServiceInterface $notificationService
    ) {
    }

    public function handle(MealPlanCustomizedEvent $event): void
    {
        try {
            $title = 'Meal Plan Customized';
            $body = "Your meal plan '{$event->planName}' has been customized successfully!";
            $data = [
                'plan_uuid' => $event->planUuid,
                'plan_name' => $event->planName,
                'customization_details' => $event->customizationDetails,
            ];

            $this->notificationService->sendToUser(
                $event->userUuid,
                NotificationTypeEnum::MEAL_PLAN_CUSTOMIZED,
                $title,
                $body,
                $data
            );

            Log::info('Meal plan customized notification sent', [
                'user_uuid' => $event->userUuid,
                'plan_uuid' => $event->planUuid,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send meal plan customized notification', [
                'user_uuid' => $event->userUuid,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
