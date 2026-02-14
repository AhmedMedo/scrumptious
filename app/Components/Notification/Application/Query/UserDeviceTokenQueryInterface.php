<?php

namespace App\Components\Notification\Application\Query;

use App\Components\Notification\Data\Enums\DeviceTypeEnum;
use Illuminate\Support\Collection;

interface UserDeviceTokenQueryInterface
{
    public function getActiveTokensByUserUuid(string $userUuid): Collection;

    public function getActiveTokensByUserUuids(array $userUuids): Collection;

    public function getTokensByDeviceType(string $userUuid, DeviceTypeEnum $deviceType): Collection;

    public function findByToken(string $token): ?\App\Components\Notification\Data\Entity\UserDeviceTokenEntity;

    public function getAllActiveTokens(): Collection;
}
