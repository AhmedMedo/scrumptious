<?php

namespace App\Components\Auth\Infrastructure\Repository;

use App\Components\Auth\Application\Repository\UserVerificationRepositoryInterface;
use App\Components\Auth\Data\Entity\UserVerificationEntity;
use App\Components\Auth\Data\Enums\UserVerificationTypeEnum;
use Illuminate\Support\Str;

class UserVerificationRepository implements UserVerificationRepositoryInterface
{
    public function create(string $userUuid, UserVerificationTypeEnum $typeEnum, bool $isSandbox = false): UserVerificationEntity
    {
        return  UserVerificationEntity::firstOrCreate(
            [
                'user_uuid'  => $userUuid,
                'type'       => $typeEnum->value,
            ],
            [
                'user_uuid'  => $userUuid,
                'type'       => $typeEnum->value,
                'token'      => Str::random(32),
//                'otp'        => \App::environment('local', 'development') ? config('giftit.default_otp') : rand(1000, 9999),
                'otp'        => config('app.default_otp')

            ]
        );
    }

    public function delete(string $userUuid, UserVerificationTypeEnum $typeEnum): void
    {
        UserVerificationEntity::where('user_uuid', $userUuid)
            ->where('type', $typeEnum->value)
            ->delete();
    }

    //TODO::should have a better way to update otp
    public function updateByToken(string $token): int
    {
        $userVerificationToken = UserVerificationEntity::where('token', $token)->first();
        if (!$userVerificationToken) {
            throw new \Exception('Invalid token');
        }
        return  $userVerificationToken
            ->update(['otp' => \App::environment('local', 'development') ? config('giftit.default_otp') : rand(1000, 9999), ]);
    }

    public function findByToken(string $token): ?UserVerificationEntity
    {
        return UserVerificationEntity::where('token', $token)->first();
    }
}
