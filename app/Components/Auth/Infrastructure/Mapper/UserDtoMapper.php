<?php

namespace App\Components\Auth\Infrastructure\Mapper;

use App\Components\Auth\Application\Mapper\UserDtoMapperInterface;
use App\Components\Auth\Domain\DTO\UserDto;

class UserDtoMapper implements UserDtoMapperInterface
{
    public function map($user): UserDto
    {
        return new UserDto(
            uuid: $user->getKey(),
            firstName: $user->first_name,
            lastName: $user->last_name,
            email: $user->email,
            phoneNumber: $user->phone_number,
            country: $user->country?->name,
            isActive: $user->is_active,
            image: $user->getFirstMediaUrl('profile') ?? null,
            address: $user->address,
            postalCode: $user->postal_code,
            createdAt: $user->created_at,
            updatedAt: $user->updated_at,
            countryUuid: $user->country?->getKey(),
            countryIsoCode: $user->country?->iso_code,
            isEmailVerified: $user->email_verified_at !== null,
            isPhoneVerified: $user->phone_verified_at !== null,
            isSandbox: $user->is_sandbox
        );
    }
}
