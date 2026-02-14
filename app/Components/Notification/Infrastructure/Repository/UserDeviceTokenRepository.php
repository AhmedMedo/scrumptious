<?php

namespace App\Components\Notification\Infrastructure\Repository;

use App\Components\Notification\Application\Repository\UserDeviceTokenRepositoryInterface;
use App\Components\Notification\Data\Entity\UserDeviceTokenEntity;
use App\Components\Notification\Data\Enums\DeviceTypeEnum;

class UserDeviceTokenRepository implements UserDeviceTokenRepositoryInterface
{
    public function create(array $data): UserDeviceTokenEntity
    {
        return UserDeviceTokenEntity::create($data);
    }

    public function findByUuid(string $uuid): ?UserDeviceTokenEntity
    {
        return UserDeviceTokenEntity::where('uuid', $uuid)->first();
    }

    public function findByToken(string $token): ?UserDeviceTokenEntity
    {
        return UserDeviceTokenEntity::where('device_token', $token)->first();
    }

    public function update(string $uuid, array $data): bool
    {
        return UserDeviceTokenEntity::where('uuid', $uuid)->update($data);
    }

    public function delete(string $uuid): bool
    {
        return UserDeviceTokenEntity::where('uuid', $uuid)->delete();
    }

    public function deactivate(string $uuid): bool
    {
        return $this->update($uuid, ['is_active' => false]);
    }

    public function activate(string $uuid): bool
    {
        return $this->update($uuid, ['is_active' => true]);
    }

    public function registerToken(string $userUuid, string $deviceToken, DeviceTypeEnum $deviceType, ?string $deviceName = null): UserDeviceTokenEntity
    {
        $existingToken = $this->findByToken($deviceToken);

        if ($existingToken) {
            $this->update($existingToken->uuid, [
                'user_uuid' => $userUuid,
                'device_type' => $deviceType,
                'device_name' => $deviceName,
                'is_active' => true,
                'last_used_at' => now(),
            ]);

            return $existingToken->fresh();
        }

        return $this->create([
            'user_uuid' => $userUuid,
            'device_token' => $deviceToken,
            'device_type' => $deviceType,
            'device_name' => $deviceName,
            'is_active' => true,
            'last_used_at' => now(),
        ]);
    }

    public function deactivateOldTokens(string $userUuid, string $currentToken): void
    {
        UserDeviceTokenEntity::where('user_uuid', $userUuid)
            ->where('device_token', '!=', $currentToken)
            ->where('is_active', true)
            ->update(['is_active' => false]);
    }
}
