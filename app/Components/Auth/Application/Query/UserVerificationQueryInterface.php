<?php

namespace App\Components\Auth\Application\Query;

use App\Components\Auth\Domain\DTO\UserVerificationDto;

interface UserVerificationQueryInterface
{
    public function findUserVerificationByToken(string $token): ?UserVerificationDTO;

    public function findUserVerificationByTokenAndOtp(string $token, string $otp): ?UserVerificationDTO;

    public function findUserVerificationByTokenAndTypeAndOtp(string $token, string $otp, string $type): ?UserVerificationDTO;
}
