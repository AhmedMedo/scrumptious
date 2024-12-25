<?php

namespace App\Components\Auth\Application\Query;

interface UserOldPasswordQueryInterface
{
    public function checkIfPasswordExist(string $userUuid, string $password): bool;
}
