<?php

namespace App\Components\Notification\Application\Service;

use App\Components\Notification\Data\Enums\NotificationTypeEnum;

interface EmailNotificationServiceInterface
{
    public function send(string $userUuid, NotificationTypeEnum $type, string $title, string $body, ?array $data = null): bool;

    public function sendToMultiple(array $userUuids, NotificationTypeEnum $type, string $title, string $body, ?array $data = null): array;
}
