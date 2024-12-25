<?php

namespace App\Components\Auth\Application\Mapper;

use App\Components\Auth\Domain\DTO\UserDto;
use App\Components\Auth\Presentation\ViewModel\User\UserViewModel;

interface UserViewModelMapperInterface
{
    public function map(UserDto $userDto): UserViewModel;
}
