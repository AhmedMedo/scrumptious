<?php

namespace App\Components\Auth\Application\Factory;

use App\Components\Auth\Data\Entity\UserVerificationEntity;
use App\Components\Auth\Domain\DTO\UserVerificationDto;

interface UserVerificationFactoryInterface
{
    public function createFromEntity(UserVerificationEntity $userVerificationEntity): UserVerificationDto;
}
