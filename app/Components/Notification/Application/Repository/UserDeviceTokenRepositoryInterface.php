<?php

namespace App\Components\Notification\Application\Repository;

use App\Components\Notification\Data\Entity\UserDeviceTokenEntity;
use App\Components\Notification\Data\Enums\DeviceTypeEnum;

interface UserDeviceTokenRepositoryInterface
{
    public function create(array $data): UserDeviceTokenEntity;

    public function findByUuid(string $uuid): ?UserDeviceTokenEntity;

    public function findByToken(string $token): ?UserDeviceTokenEntity;

    public function update(string $uuid, array $data): bool;

    public function delete(string $uuid): bool;

    public function deactivate(string $uuid): bool;

    public function activate(string $uuid): bool;

    public function registerToken(string $userUuid, string $deviceToken, DeviceTypeEnum $deviceType, ?string $deviceName = null): UserDeviceTokenEntity;

    public function deactivateOldTokens(string $userUuid, string $currentToken): void;
}
