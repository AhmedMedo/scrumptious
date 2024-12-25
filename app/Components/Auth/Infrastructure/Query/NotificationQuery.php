<?php

namespace App\Components\Auth\Infrastructure\Query;

use App\Components\Auth\Application\Query\NotificationQueryInterface;
use App\Components\Auth\Data\Entity\NotificationEntity;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationQuery implements NotificationQueryInterface
{
    public function getNotificationsByUserUuid(string $userUuid): LengthAwarePaginator
    {
        return NotificationEntity::where('user_uuid', $userUuid)
            ->orderBy('created_at', 'desc')
            ->paginate(request()->get('per_page', 10));
    }
}
