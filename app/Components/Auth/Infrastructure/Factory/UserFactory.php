<?php

namespace App\Components\Auth\Infrastructure\Factory;

use App\Components\Auth\Application\Factory\UserFactoryInterface;
use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Auth\Domain\DTO\UserDto;
use Carbon\Carbon;

class UserFactory implements UserFactoryInterface
{
    public function createFromEntity(UserEntity $userEntity): UserDto
    {
        return new UserDto(
            uuid: $userEntity->getKey(),
            firstName: $userEntity->first_name,
            lastName: $userEntity->last_name,
            email: $userEntity->email,
            phoneNumber: $userEntity->phone_number,
            country: $userEntity->country?->name,
            isActive: $userEntity->is_active,
            image: $userEntity->getFirstMediaUrl('profile'),
            address: $userEntity->address,
            postalCode: $userEntity->postal_code,
            createdAt: $userEntity->created_at,
            updatedAt: $userEntity->updated_at,
            countryUuid: $userEntity->country_uuid,
            countryIsoCode: $userEntity->country?->iso_code,
            isEmailVerified: $userEntity->email_verified_at !== null,
            isPhoneVerified: $userEntity->phone_verified_at !== null,
            countryCode: $userEntity->country_code,

            // New fields
            birthDate: $userEntity->birth_date ? Carbon::parse($userEntity->birth_date) : null,
            weight: $userEntity->weight !== null ? (float) $userEntity->weight : null,
            weightUnit: $userEntity->weight_unit,
            height: $userEntity->height !== null ? (float) $userEntity->height : null,
            heightUnit: $userEntity->height_unit,
            userDiet: $userEntity->user_diet,
            goal: $userEntity->goal,
            haveAllergies: $userEntity->have_allergies,
            allergies: $userEntity->allergies ? $userEntity->allergies : null,
        );
    }
}
