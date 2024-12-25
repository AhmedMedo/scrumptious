<?php

namespace App\Components\Auth\Infrastructure\Query;

use App\Components\Auth\Application\Factory\UserVerificationFactoryInterface;
use App\Components\Auth\Application\Query\UserVerificationQueryInterface;
use App\Components\Auth\Data\Entity\UserVerificationEntity;
use App\Components\Auth\Domain\DTO\UserVerificationDto;

class UserVerificationQuery implements UserVerificationQueryInterface
{
    public function __construct(
        private readonly UserVerificationFactoryInterface $userVerificationFactory,
    ) {
    }

    public function findUserVerificationByToken(string $token): ?UserVerificationDTO
    {
        $userVerification = UserVerificationEntity::whereToken($token)->first();
        if (!$userVerification) {
            return null;
        }
        return $this->userVerificationFactory->createFromEntity($userVerification);
    }


    public function findUserVerificationByTokenAndOtp(string $token, string $otp): ?UserVerificationDTO
    {
        $userVerification = UserVerificationEntity::whereToken($token)->whereOtp($otp)->first();
        if (!$userVerification) {
            return null;
        }
        return $this->userVerificationFactory->createFromEntity($userVerification);
    }

    public function findUserVerificationByTokenAndTypeAndOtp(string $token, string $otp, string $type): ?UserVerificationDTO
    {
        $userVerification = UserVerificationEntity::whereToken($token)->whereOtp($otp)->whereType($type)->first();
        if (!$userVerification) {
            return null;
        }
        return $this->userVerificationFactory->createFromEntity($userVerification);
    }
}
