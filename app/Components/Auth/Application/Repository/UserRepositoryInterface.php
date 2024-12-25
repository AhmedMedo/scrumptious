<?php

namespace App\Components\Auth\Application\Repository;

use App\Components\Auth\Data\Entity\UserEntity;

interface UserRepositoryInterface
{
    public function create(array $data): UserEntity;
    public function update(string $userUuid, array $data, ?string $imagePath = null, bool $resetPhoneVerification = true): bool;
    public function delete(string $userUuid): bool;
}
