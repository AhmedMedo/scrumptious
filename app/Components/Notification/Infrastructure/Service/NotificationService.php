<?php

namespace App\Components\Notification\Infrastructure\Service;

use App\Components\Notification\Application\Query\NotificationQueryInterface;
use App\Components\Notification\Application\Repository\NotificationRepositoryInterface;
use App\Components\Notification\Application\Service\EmailNotificationServiceInterface;
use App\Components\Notification\Application\Service\FcmServiceInterface;
use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Components\Notification\Data\DTO\SendNotificationDto;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;
use App\Components\Notification\Infrastructure\Jobs\SendEmailNotificationJob;
use App\Components\Notification\Infrastructure\Jobs\SendFcmNotificationJob;
use Illuminate\Support\Facades\Log;

class NotificationService implements NotificationServiceInterface
{
    public function __construct(
        private readonly NotificationRepositoryInterface $notificationRepository,
        private readonly NotificationQueryInterface $notificationQuery,
    ) {
    }

    public function send(SendNotificationDto $dto): array
    {
        $results = [
            'database' => [],
            'fcm' => [],
            'email' => [],
        ];

        $userUuids = $dto->userUuids();

        foreach ($userUuids as $userUuid) {
            if ($dto->shouldSaveToDatabase()) {
                try {
                    $notification = $this->notificationRepository->createForUser(
                        $userUuid,
                        $dto->type(),
                        $dto->title(),
                        $dto->body(),
                        $dto->data()
                    );

                    $results['database'][] = [
                        'user_uuid' => $userUuid,
                        'notification_uuid' => $notification->uuid,
                        'status' => 'success',
                    ];
                } catch (\Exception $e) {
                    Log::error('Failed to save notification to database', [
                        'user_uuid' => $userUuid,
                        'error' => $e->getMessage(),
                    ]);

                    $results['database'][] = [
                        'user_uuid' => $userUuid,
                        'status' => 'failed',
                        'error' => $e->getMessage(),
                    ];
                }
            }

            if ($dto->shouldSendFcm()) {
                SendFcmNotificationJob::dispatch(
                    $userUuid,
                    $dto->title(),
                    $dto->body(),
                    $dto->data() ?? []
                )->onQueue(config('notification.queue', 'notifications'));

                $results['fcm'][] = [
                    'user_uuid' => $userUuid,
                    'status' => 'queued',
                ];
            }

            if ($dto->shouldSendEmail()) {
                SendEmailNotificationJob::dispatch(
                    $userUuid,
                    $dto->type(),
                    $dto->title(),
                    $dto->body(),
                    $dto->data()
                )->onQueue(config('notification.queue', 'notifications'));

                $results['email'][] = [
                    'user_uuid' => $userUuid,
                    'status' => 'queued',
                ];
            }
        }

        return $results;
    }

    public function sendToUser(string $userUuid, NotificationTypeEnum $type, string $title, string $body, ?array $data = null): bool
    {
        $dto = new SendNotificationDto(
            userUuids: $userUuid,
            type: $type,
            title: $title,
            body: $body,
            data: $data
        );

        $results = $this->send($dto);

        return !empty($results['database']) && $results['database'][0]['status'] === 'success';
    }

    public function sendToMultipleUsers(array $userUuids, NotificationTypeEnum $type, string $title, string $body, ?array $data = null): array
    {
        $dto = new SendNotificationDto(
            userUuids: $userUuids,
            type: $type,
            title: $title,
            body: $body,
            data: $data
        );

        return $this->send($dto);
    }

    public function markAsRead(string $notificationUuid): bool
    {
        try {
            return $this->notificationRepository->markAsRead($notificationUuid);
        } catch (\Exception $e) {
            Log::error('Failed to mark notification as read', [
                'notification_uuid' => $notificationUuid,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function markAllAsRead(string $userUuid): bool
    {
        try {
            return $this->notificationRepository->markAllAsReadForUser($userUuid);
        } catch (\Exception $e) {
            Log::error('Failed to mark all notifications as read', [
                'user_uuid' => $userUuid,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function deleteNotification(string $notificationUuid): bool
    {
        try {
            return $this->notificationRepository->delete($notificationUuid);
        } catch (\Exception $e) {
            Log::error('Failed to delete notification', [
                'notification_uuid' => $notificationUuid,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function getUnreadCount(string $userUuid): int
    {
        return $this->notificationQuery->getUnreadCount($userUuid);
    }
}
