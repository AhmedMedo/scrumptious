<?php

namespace App\Components\Auth\Domain\DTO;

use Carbon\Carbon;

class UserDto
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
        public readonly string $phoneNumber,
        public readonly ?string $country,
        public readonly bool $isActive,
        public readonly ?string $image,
        public readonly ?string $address,
        public readonly ?string $postalCode,
        public readonly Carbon $createdAt,
        public readonly Carbon $updatedAt,
        public readonly ?string $countryUuid = null,
        public readonly ?string $countryIsoCode = null,
        public readonly bool $isEmailVerified = false,
        public readonly bool $isPhoneVerified = false,
        public readonly ?string $countryCode = null,

        // New fields
        public readonly ?Carbon $birthDate = null,
        public readonly ?float $weight = null,
        public readonly ?string $weightUnit = null,
        public readonly ?float $height = null,
        public readonly ?string $heightUnit = null,
        public readonly ?string $userDiet = null,
        public readonly ?string $goal = null,
        public readonly ?bool $haveAllergies = null,
        public readonly ?array $allergies = null,
        public readonly ?string $gender = null
    ) {
    }
    public function Uuid(): string
    {
        return $this->uuid;
    }

    public function FirstName(): string
    {
        return $this->firstName;
    }

    public function LastName(): string
    {
        return $this->lastName;
    }

    public function fullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function Email(): string
    {
        return $this->email;
    }

    public function PhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function Country(): ?string
    {
        return $this->country;
    }

    public function IsActive(): bool
    {
        return $this->isActive;
    }

    public function Image(): ?string
    {
        return $this->image;
    }

    public function CreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function UpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public function Address(): ?string
    {
        return $this->address;
    }

    public function PostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function CountryUuid(): ?string
    {
        return $this->countryUuid;
    }

    public function CountryIsoCode(): ?string
    {
        return $this->countryIsoCode;
    }

    public function IsEmailVerified(): bool
    {
        return $this->isEmailVerified;
    }

    public function IsPhoneVerified(): bool
    {
        return $this->isPhoneVerified;
    }

    public function CountryCode(): ?string
    {
        return $this->countryCode;
    }

// ---- New getters ---- //

    public function BirthDate(): ?Carbon
    {
        return $this->birthDate;
    }

    public function Weight(): ?float
    {
        return $this->weight;
    }

    public function WeightUnit(): ?string
    {
        return $this->weightUnit;
    }

    public function Height(): ?float
    {
        return $this->height;
    }

    public function HeightUnit(): ?string
    {
        return $this->heightUnit;
    }

    public function UserDiet(): ?string
    {
        return $this->userDiet;
    }

    public function Goal(): ?string
    {
        return $this->goal;
    }

    public function HaveAllergies(): ?bool
    {
        return $this->haveAllergies;
    }

    public function Allergies(): ?array
    {
        return $this->allergies;
    }


    public function Gender(): ?string
    {
        return $this->gender;
    }

}
