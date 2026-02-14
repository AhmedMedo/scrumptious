<?php

namespace App\Components\Notification\Infrastructure\Repository;

use App\Components\Notification\Application\Repository\NotificationRepositoryInterface;
use App\Components\Notification\Data\Entity\NotificationEntity;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function create(array $data): NotificationEntity
    {
        return NotificationEntity::create($data);
    }

    public function findByUuid(string $uuid): ?NotificationEntity
    {
        return NotificationEntity::where('uuid', $uuid)->first();
    }

    public function update(string $uuid, array $data): bool
    {
        return NotificationEntity::where('uuid', $uuid)->update($data);
    }

    public function delete(string $uuid): bool
    {
        return NotificationEntity::where('uuid', $uuid)->delete();
    }

    public function markAsRead(string $uuid): bool
    {
        return $this->update($uuid, [
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function markAllAsReadForUser(string $userUuid): bool
    {
        return NotificationEntity::where('user_uuid', $userUuid)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    public function createForUser(string $userUuid, NotificationTypeEnum $type, string $title, string $body, ?array $data = null): NotificationEntity
    {
        return $this->create([
            'user_uuid' => $userUuid,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);
    }
}
