<?php

namespace App\Components\Auth\Application\Repository;

use App\Components\Auth\Data\Entity\UserOldPasswordEntity;

interface UserOldPasswordRepositoryInterface
{
    public function create(string $userUuid, string $password): UserOldPasswordEntity;
    public function deleteForUser(string $userUuid): bool;
}
