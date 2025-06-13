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
            uuid: $userDto->Uuid(),
            email: $userDto->Email(),
            firstName: $userDto->FirstName(),
            lastName: $userDto->LastName(),
            phoneNumber: $userDto->PhoneNumber(),
            country: $userDto->Country(),
            address: $userDto->Address(),
            postalCode: $userDto->PostalCode(),
            isActive: $userDto->IsActive(),
            image: $userDto->Image(),
            createdAt: $userDto->CreatedAt(),
            updatedAt: $userDto->UpdatedAt(),
            countryUuid: $userDto->CountryUuid(),
            countryIsoCode: $userDto->CountryIsoCode(),
            isEmailVerified: $userDto->IsEmailVerified(),
            isPhoneVerified: $userDto->IsPhoneVerified(),
            countryCode: $userDto->CountryCode(),

            // New fields
            birthDate: $userDto->BirthDate(),
            weight: $userDto->Weight(),
            weightUnit: $userDto->WeightUnit(),
            height: $userDto->Height(),
            heightUnit: $userDto->HeightUnit(),
            userDiet: $userDto->UserDiet(),
            goal: $userDto->Goal(),
            haveAllergies: $userDto->HaveAllergies(),
            allergies: $userDto->Allergies(),
            gender: $userDto->Gender(),
        );
    }
}
