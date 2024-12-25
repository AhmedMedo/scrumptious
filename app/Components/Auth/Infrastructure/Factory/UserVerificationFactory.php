<?php

namespace App\Components\Auth\Infrastructure\Factory;

use App\Components\Auth\Application\Factory\UserVerificationFactoryInterface;
use App\Components\Auth\Data\Entity\UserVerificationEntity;
use App\Components\Auth\Data\Enums\UserVerificationTypeEnum;
use App\Components\Auth\Domain\DTO\UserVerificationDto;

class UserVerificationFactory implements UserVerificationFactoryInterface
{
    public function createFromEntity(UserVerificationEntity $userVerificationEntity): UserVerificationDto
    {
        return new UserVerificationDto(
            uuid: $userVerificationEntity->getKey(),
            userUuid: $userVerificationEntity->user_uuid,
            token: $userVerificationEntity->token,
            type: UserVerificationTypeEnum::from($userVerificationEntity->type),
            otp: $userVerificationEntity->otp,
            phoneNumber: $userVerificationEntity?->user?->phone_number
        );
    }
}
