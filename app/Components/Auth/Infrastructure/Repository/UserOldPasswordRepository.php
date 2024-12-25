<?php

namespace App\Components\Auth\Infrastructure\Repository;

use App\Components\Auth\Application\Repository\UserOldPasswordRepositoryInterface;
use App\Components\Auth\Data\Entity\UserOldPasswordEntity;
use Illuminate\Support\Facades\Hash;

class UserOldPasswordRepository implements UserOldPasswordRepositoryInterface
{

    public function create(string $userUuid, string $password): UserOldPasswordEntity
    {
        return UserOldPasswordEntity::create([
            'user_uuid' => $userUuid,
            'old_password' => Hash::make($password),
        ]);
    }

    public function deleteForUser(string $userUuid): bool
    {
       return UserOldPasswordEntity::query()->where('user_uuid', $userUuid)->delete();
    }
}
