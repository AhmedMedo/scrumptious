<?php

namespace App\Components\Auth\Domain\DTO;

use App\Components\Auth\Data\Enums\UserVerificationTypeEnum;
use FontLib\Table\Type\prep;

class UserVerificationDto
{
    public function __construct(
        private readonly string $uuid,
        private readonly string $userUuid,
        private readonly string $token,
        private readonly UserVerificationTypeEnum $type,
        private readonly string $otp,
        private readonly ?string $phoneNumber = null
    ) {
    }

    public function Uuid(): string
    {
        return $this->uuid;
    }

    public function UserUuid(): string
    {
        return $this->userUuid;
    }

    public function Token(): string
    {
        return $this->token;
    }

    public function Type(): UserVerificationTypeEnum
    {
        return $this->type;
    }

    public function Otp(): string
    {
        return $this->otp;
    }

    public function PhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }
}
