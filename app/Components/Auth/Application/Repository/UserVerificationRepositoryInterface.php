<?php

namespace App\Components\Auth\Application\Repository;

use App\Components\Auth\Data\Entity\UserVerificationEntity;
use App\Components\Auth\Data\Enums\UserVerificationTypeEnum;

interface UserVerificationRepositoryInterface
{
    public function create(string $userUuid, UserVerificationTypeEnum $typeEnum, bool $isSandbox = false): UserVerificationEntity;
    public function delete(string $userUuid, UserVerificationTypeEnum $typeEnum): void;
    public function updateByToken(string $token): int;

    public function findByToken(string $token): ?UserVerificationEntity;
}
