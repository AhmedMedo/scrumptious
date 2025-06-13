<?php

namespace App\Components\Auth\Infrastructure\Mapper;

use App\Components\Auth\Application\Mapper\UserDtoMapperInterface;
use App\Components\Auth\Domain\DTO\UserDto;
use Carbon\Carbon;

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
            // New fields
            birthDate: $user->birth_date ? Carbon::parse($user->birth_date) : null,
            weight: $user->weight !== null ? (float) $user->weight : null,
            weightUnit: $user->weight_unit,
            height: $user->height !== null ? (float) $user->height : null,
            heightUnit: $user->height_unit,
            userDiet: $user->user_diet,
            goal: $user->goal,
            haveAllergies: $user->have_allergies,
            allergies: $user->allergies ? $user->allergies : null,
            gender: $user->gender,
        );
    }
}
