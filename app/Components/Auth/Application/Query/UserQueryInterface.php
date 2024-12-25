<?php

namespace App\Components\Auth\Application\Query;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Auth\Domain\DTO\UserDto;

interface UserQueryInterface
{
    public function findByUuid(string $uuid): UserDto;

    public function findUserEntityByUuid(string $uuid): ?UserEntity;

    public function findUserByEmail(string $email): UserEntity;

    public function findUserByPhone(string $phone): UserEntity;

    public function findUserByVerificationCode(string $code): UserEntity;

    public function findUserByForgetPasswordToken(string $code): UserEntity;

    public function findUserByEmailVerificationToken(string $token): UserEntity;

    public function userWithPhoneExists(string $phone): bool;

    public function countPhoneExists(string $phone): int;
}
