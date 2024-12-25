<?php

namespace App\Components\Auth\Application\Query;

use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationQueryInterface
{
    public function getNotificationsByUserUuid(string $userUuid): LengthAwarePaginator;
}
