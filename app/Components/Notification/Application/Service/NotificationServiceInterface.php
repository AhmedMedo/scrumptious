<?php

namespace App\Components\Notification\Application\Service;

use App\Components\Notification\Data\DTO\SendNotificationDto;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;

interface NotificationServiceInterface
{
    public function send(SendNotificationDto $dto): array;

    public function sendToUser(string $userUuid, NotificationTypeEnum $type, string $title, string $body, ?array $data = null): bool;

    public function sendToMultipleUsers(array $userUuids, NotificationTypeEnum $type, string $title, string $body, ?array $data = null): array;

    public function markAsRead(string $notificationUuid): bool;

    public function markAllAsRead(string $userUuid): bool;

    public function deleteNotification(string $notificationUuid): bool;

    public function getUnreadCount(string $userUuid): int;
}
