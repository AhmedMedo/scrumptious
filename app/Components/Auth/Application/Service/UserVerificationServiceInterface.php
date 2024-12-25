<?php

namespace App\Components\Auth\Application\Service;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Auth\Data\Enums\UserVerificationTypeEnum;
use App\Components\Auth\Domain\DTO\UserVerificationDto;

interface UserVerificationServiceInterface
{
    public function addVerificationOtp(string $userUuid, UserVerificationTypeEnum $typeEnum , bool $sendSms = true): UserVerificationDto;

    public function verifyOtp(string $token, string $otp): UserEntity;

    public function resendOtp(string $token): int;

    public function verifyEmail(string $token): void;

    public function resendEmailVerification(string $email): void;
}
