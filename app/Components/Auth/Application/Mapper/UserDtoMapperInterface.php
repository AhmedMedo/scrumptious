<?php

namespace App\Components\Auth\Application\Mapper;

use App\Components\Auth\Domain\DTO\UserDto;

interface UserDtoMapperInterface
{
    public function map($user): UserDto;
}
