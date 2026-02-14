<?php

namespace App\Components\Notification\Infrastructure\Listeners;

use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;
use App\Components\Notification\Infrastructure\Events\AdminBroadcastEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendAdminBroadcastNotification implements ShouldQueue
{
    public function __construct(
        private readonly NotificationServiceInterface $notificationService
    ) {
    }

    public function handle(AdminBroadcastEvent $event): void
    {
        try {
            $this->notificationService->sendToMultipleUsers(
                $event->userUuids,
                NotificationTypeEnum::ADMIN_MESSAGE,
                $event->title,
                $event->body,
                $event->data
            );

            Log::info('Admin broadcast notification sent', [
                'broadcast_uuid' => $event->broadcastUuid,
                'user_count' => count($event->userUuids),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send admin broadcast notification', [
                'broadcast_uuid' => $event->broadcastUuid,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
