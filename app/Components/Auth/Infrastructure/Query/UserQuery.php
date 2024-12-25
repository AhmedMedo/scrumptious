<?php

namespace App\Components\Auth\Infrastructure\Query;

use App\Components\Auth\Application\Factory\UserFactoryInterface;
use App\Components\Auth\Application\Query\UserQueryInterface;
use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Auth\Data\Enums\RoleEnum;
use App\Components\Auth\Domain\DTO\UserDto;
use App\Components\Auth\Domain\Exception\UserNotFoundException;

class UserQuery implements UserQueryInterface
{
    public function __construct(private readonly UserFactoryInterface $userFactory)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    public function findUserByEmail(string $email): UserEntity
    {
//        $user = UserEntity::query()->whereDoesntHave('roles', function ($q) { $q->where('name', RoleEnum::GUEST->value); })->whereEmail($email)->first();
        $user = UserEntity::query()->whereEmail($email)->first();

        if (!$user) {
            throw new UserNotFoundException('email');
        }
        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function findUserByForgetPasswordToken(string $code): UserEntity
    {
        $user = UserEntity::whereForgetPasswordToken($code)->first();
        if (!$user) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function findUserByVerificationCode(string $code): UserEntity
    {
        $user = UserEntity::whereVerificationCode($code)->first();
        if (!$user) {
            throw new UserNotFoundException('code');
        }
        return $user;
    }

    public function findUserByPhone(string $phone): UserEntity
    {
//        $user = UserEntity::query()->whereDoesntHave('roles', function ($q) { $q->where('name', RoleEnum::GUEST->value); })->wherePhoneNumber($phone)->first();
        $user = UserEntity::query()->wherePhoneNumber($phone)->first();

        if (!$user) {
            throw new UserNotFoundException('phone');
        }
        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function findByUuid(string $uuid): UserDto
    {
        $user = UserEntity::whereUuid($uuid)->first();
        if (!$user) {
            throw new UserNotFoundException();
        }
        return $this->userFactory->createFromEntity($user);
    }

    public function findUserEntityByUuid(string $uuid): ?UserEntity
    {
        $user = UserEntity::whereUuid($uuid)->first();
        if (!$user) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function findUserByEmailVerificationToken(string $token): UserEntity
    {
        $user = UserEntity::where('verification_code', '=', $token)->first();
        if (!$user) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    public function userWithPhoneExists(string $phone): bool
    {
        return UserEntity::wherePhoneNumber($phone)->exists();
    }

    public function countPhoneExists(string $phone): int
    {
        return UserEntity::wherePhoneNumber($phone)->count();
    }
}
