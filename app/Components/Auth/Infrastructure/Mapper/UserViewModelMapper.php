<?php

namespace App\Components\Auth\Infrastructure\Mapper;

use App\Components\Auth\Application\Mapper\UserViewModelMapperInterface;
use App\Components\Auth\Domain\DTO\UserDto;
use App\Components\Auth\Presentation\ViewModel\User\UserViewModel;

class UserViewModelMapper implements UserViewModelMapperInterface
{
    public function map(UserDto $userDto): UserViewModel
    {
        return new UserViewModel(
            uuid: $userDto->uuid(),
            email: $userDto->email(),
            firstName: $userDto->firstName(),
            lastName: $userDto->lastName(),
            phoneNumber: $userDto->phoneNumber(),
            country: $userDto->country(),
            address: $userDto->address(),
            postalCode: $userDto->postalCode(),
            isActive: $userDto->isActive(),
            image: $userDto->image(),
            createdAt: $userDto->createdAt(),
            updatedAt: $userDto->updatedAt(),
            countryUuid: $userDto->countryUuid(),
            countryIsoCode: $userDto->countryIsoCode(),
            isEmailVerified: $userDto->isEmailVerified(),
            isPhoneVerified: $userDto->isPhoneVerified(),
            isSandbox: $userDto->isSandbox(),
            countryCode: $userDto->CountryCode()
        );
    }
}
