<?php

namespace App\Components\Auth\Infrastructure\Query;


use App\Components\Auth\Application\Query\UserOldPasswordQueryInterface;
use App\Components\Auth\Data\Entity\UserOldPasswordEntity;
use Illuminate\Support\Facades\Hash;

class UserOldPasswordQuery implements UserOldPasswordQueryInterface
{
    public function checkIfPasswordExist(string $userUuid, string $password): bool
    {
        $oldPasswords = UserOldPasswordEntity::query()->where('user_uuid', $userUuid)->pluck('old_password')->toArray();
        foreach ($oldPasswords as $oldPassword) {
            if (Hash::check($password, $oldPassword)) {
                return true;  // Password exists in the history
            }
        }
        return false;
    }
}
