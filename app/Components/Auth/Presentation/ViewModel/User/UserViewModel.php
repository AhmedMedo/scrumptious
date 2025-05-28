<?php

namespace App\Components\Auth\Presentation\ViewModel\User;

use Carbon\Carbon;
use OpenApi\Attributes as OA;

#[OA\Schema(
    title: "UserViewModel",
    description: "User View Model",
    required: ["uuid", "email", "first_name", "last_name", "phone_number", "is_active", "created_at", "updated_at"],
    properties: [
        new OA\Property(property: "uuid", type: "string"),
        new OA\Property(property: "email", type: "string"),
        new OA\Property(property: "first_name", type: "string"),
        new OA\Property(property: "last_name", type: "string"),
        new OA\Property(property: "phone_number", type: "string"),
        new OA\Property(property: 'country', properties: [
            new OA\Property(property: 'uuid', type: 'string'),
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'iso2_code', type: 'string'),
        ], type: 'object'),
        new OA\Property(property: "address", type: "string"),
        new OA\Property(property: "postal_code", type: "string"),
        new OA\Property(property: "image", type: "string"),
        new OA\Property(property: "is_active", type: "boolean"),
        new OA\Property(property: "is_email_verified", type: "boolean"),
        new OA\Property(property: "is_phone_verified", type: "boolean"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time"),

        // New fields
        new OA\Property(property: "birth_date", type: "string", format: "date", nullable: true),
        new OA\Property(property: "weight", type: "number", format: "float", nullable: true),
        new OA\Property(property: "weight_unit", type: "string", nullable: true),
        new OA\Property(property: "height", type: "number", format: "float", nullable: true),
        new OA\Property(property: "height_unit", type: "string", nullable: true),
        new OA\Property(property: "user_diet", type: "string", nullable: true),
        new OA\Property(property: "goal", type: "string", nullable: true),
        new OA\Property(property: "have_allergies", type: "boolean", nullable: true),
        new OA\Property(property: "allergies", type: "array", nullable: true, items: new OA\Items(type: "string")),
    ],
    type: "object",
)]

class UserViewModel
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $email,
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly string $phoneNumber,
        private readonly ?string $country,
        private readonly ?string $address,
        private readonly ?string $postalCode,
        private readonly bool $isActive,
        private readonly ?string $image,
        private readonly Carbon $createdAt,
        private readonly Carbon $updatedAt,
        private readonly ?string $countryUuid = null,
        private readonly ?string $countryIsoCode = null,
        private readonly bool $isEmailVerified = false,
        private readonly bool $isPhoneVerified = false,
        private readonly ?string $countryCode = null,

        // New fields
        private readonly ?Carbon $birthDate = null,
        private readonly ?float $weight = null,
        private readonly ?string $weightUnit = null,
        private readonly ?float $height = null,
        private readonly ?string $heightUnit = null,
        private readonly ?string $userDiet = null,
        private readonly ?string $goal = null,
        private readonly ?bool $haveAllergies = null,
        private readonly ?array $allergies = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'email' => $this->email,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phoneNumber,
            'country_code' => $this->countryCode,
            'full_phone' => [
                'phone' => str_replace($this->countryCode, '', $this->phoneNumber),
                'country_code' => $this->countryCode,
            ],
            'country' => $this->countryUuid ? [
                'uuid' => $this->countryUuid,
                'name' => $this->country,
                'iso_code' => $this->countryIsoCode,
            ] : null,
            'address' => $this->address,
            'postal_code' => $this->postalCode,
            'image' => !empty($this->image) ? $this->image : null,
            'is_active' => $this->isActive,
            'is_email_verified' => $this->isEmailVerified,
            'is_phone_verified' => $this->isPhoneVerified,
            'force_logout' => !$this->isEmailVerified || !$this->isPhoneVerified,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,

            // New fields
            'birth_date' => $this->birthDate?->toDateString(),
            'weight' => $this->weight,
            'weight_unit' => $this->weightUnit,
            'height' => $this->height,
            'height_unit' => $this->heightUnit,
            'user_diet' => $this->userDiet,
            'goal' => $this->goal,
            'have_allergies' => $this->haveAllergies,
            'allergies' => $this->allergies,
        ];
    }
}
