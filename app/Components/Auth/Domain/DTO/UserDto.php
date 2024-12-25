<?php

namespace App\Components\Auth\Domain\DTO;

use Carbon\Carbon;
use phpseclib3\Common\Functions\Strings;

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
}
