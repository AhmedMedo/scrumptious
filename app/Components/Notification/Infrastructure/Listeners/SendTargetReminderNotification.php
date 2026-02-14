<?php

namespace App\Components\Notification\Infrastructure\Listeners;

use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;
use App\Components\Notification\Infrastructure\Events\TargetReminderEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendTargetReminderNotification implements ShouldQueue
{
    public function __construct(
        private readonly NotificationServiceInterface $notificationService
    ) {
    }

    public function handle(TargetReminderEvent $event): void
    {
        try {
            $title = 'Target Reminder';
            $body = "Your target '{$event->targetTitle}' is ending in {$event->daysRemaining} days. Keep up the great work!";
            $data = [
                'target_uuid' => $event->targetUuid,
                'target_title' => $event->targetTitle,
                'end_date' => $event->endDate,
                'days_remaining' => $event->daysRemaining,
            ];

            $this->notificationService->sendToUser(
                $event->userUuid,
                NotificationTypeEnum::TARGET_REMINDER,
                $title,
                $body,
                $data
            );

            Log::info('Target reminder notification sent', [
                'user_uuid' => $event->userUuid,
                'target_uuid' => $event->targetUuid,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send target reminder notification', [
                'user_uuid' => $event->userUuid,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
