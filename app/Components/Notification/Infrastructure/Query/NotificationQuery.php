<?php

namespace App\Components\Notification\Infrastructure\Query;

use App\Components\Notification\Application\Query\NotificationQueryInterface;
use App\Components\Notification\Data\Entity\NotificationEntity;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationQuery implements NotificationQueryInterface
{
    public function getNotificationsByUserUuid(string $userUuid, ?int $perPage = null): LengthAwarePaginator
    {
        return NotificationEntity::where('user_uuid', $userUuid)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage ?? request()->get('per_page', 10));
    }

    public function getUnreadByUserUuid(string $userUuid, ?int $perPage = null): LengthAwarePaginator
    {
        return NotificationEntity::where('user_uuid', $userUuid)
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage ?? request()->get('per_page', 10));
    }

    public function getByType(string $userUuid, NotificationTypeEnum $type, ?int $perPage = null): LengthAwarePaginator
    {
        return NotificationEntity::where('user_uuid', $userUuid)
            ->where('type', $type->value)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage ?? request()->get('per_page', 10));
    }

    public function getUnreadCount(string $userUuid): int
    {
        return NotificationEntity::where('user_uuid', $userUuid)
            ->where('is_read', false)
            ->count();
    }

    public function findByUuid(string $uuid): ?NotificationEntity
    {
        return NotificationEntity::where('uuid', $uuid)->first();
    }
}
