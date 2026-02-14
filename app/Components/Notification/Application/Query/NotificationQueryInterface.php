<?php

namespace App\Components\Notification\Application\Query;

use App\Components\Notification\Data\Enums\NotificationTypeEnum;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationQueryInterface
{
    public function getNotificationsByUserUuid(string $userUuid, ?int $perPage = null): LengthAwarePaginator;

    public function getUnreadByUserUuid(string $userUuid, ?int $perPage = null): LengthAwarePaginator;

    public function getByType(string $userUuid, NotificationTypeEnum $type, ?int $perPage = null): LengthAwarePaginator;

    public function getUnreadCount(string $userUuid): int;

    public function findByUuid(string $uuid): ?\App\Components\Notification\Data\Entity\NotificationEntity;
}
