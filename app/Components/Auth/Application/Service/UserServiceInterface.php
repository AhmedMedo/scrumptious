<?php

namespace App\Components\Auth\Application\Service;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Auth\Data\Enums\UserVerificationTypeEnum;
use App\Components\Auth\Domain\DTO\UserDto;
use App\Components\Auth\Domain\DTO\UserVerificationDto;
use Illuminate\Contracts\Auth\Authenticatable;

interface UserServiceInterface
{
    public function login(array $credentials): UserEntity;

    public function user(): UserDto;

    public function isAuthenticated(): bool;

    public function userEntity(): UserEntity;

    public function register(array $data): UserVerificationDto;

    public function verify(string $code): bool;

    public function forgetPassword(string $username): UserVerificationDto;

    public function resetPassword(string $token, string $password): bool;

    public function resendOtp(string $token): int;

    public function updateProfile(string $userUuid, array $data, ?string $imagePath): UserDto;

    public function updatePassword(string $userUuid, string $oldPassword, string $password): bool;

    public function logout(): bool;

    public function deleteAccount(string $userUuid): bool;

    public function loginAsGuest(string $name, string $email, string $phone): UserVerificationDto | bool;

    public function changeUserPhoneByEmail(string $email, string $countryCode, string $phoneNumber): UserVerificationDto;

    public function changeEmail(string $userUuid, string $email);

    public function changePhone(string $userUuid, string $countryCode, string $phoneNumber);

}
