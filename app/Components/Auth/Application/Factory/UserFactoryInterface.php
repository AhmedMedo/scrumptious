<?php

namespace App\Components\Auth\Application\Factory;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Auth\Domain\DTO\UserDto;

interface UserFactoryInterface
{
    public function createFromEntity(UserEntity $userEntity): UserDto;
}
