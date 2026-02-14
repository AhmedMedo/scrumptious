<?php

namespace App\Components\Notification\Application\Repository;

use App\Components\Notification\Data\Entity\NotificationEntity;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;

interface NotificationRepositoryInterface
{
    public function create(array $data): NotificationEntity;

    public function findByUuid(string $uuid): ?NotificationEntity;

    public function update(string $uuid, array $data): bool;

    public function delete(string $uuid): bool;

    public function markAsRead(string $uuid): bool;

    public function markAllAsReadForUser(string $userUuid): bool;

    public function createForUser(string $userUuid, NotificationTypeEnum $type, string $title, string $body, ?array $data = null): NotificationEntity;
}
