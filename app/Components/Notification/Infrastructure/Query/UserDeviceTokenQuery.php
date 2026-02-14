<?php

namespace App\Components\Notification\Infrastructure\Query;

use App\Components\Notification\Application\Query\UserDeviceTokenQueryInterface;
use App\Components\Notification\Data\Entity\UserDeviceTokenEntity;
use App\Components\Notification\Data\Enums\DeviceTypeEnum;
use Illuminate\Support\Collection;

class UserDeviceTokenQuery implements UserDeviceTokenQueryInterface
{
    public function getActiveTokensByUserUuid(string $userUuid): Collection
    {
        return UserDeviceTokenEntity::where('user_uuid', $userUuid)
            ->where('is_active', true)
            ->get();
    }

    public function getActiveTokensByUserUuids(array $userUuids): Collection
    {
        return UserDeviceTokenEntity::whereIn('user_uuid', $userUuids)
            ->where('is_active', true)
            ->get();
    }

    public function getTokensByDeviceType(string $userUuid, DeviceTypeEnum $deviceType): Collection
    {
        return UserDeviceTokenEntity::where('user_uuid', $userUuid)
            ->where('device_type', $deviceType->value)
            ->where('is_active', true)
            ->get();
    }

    public function findByToken(string $token): ?UserDeviceTokenEntity
    {
        return UserDeviceTokenEntity::where('device_token', $token)->first();
    }

    public function getAllActiveTokens(): Collection
    {
        return UserDeviceTokenEntity::where('is_active', true)->get();
    }
}
