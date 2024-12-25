<?php

namespace App\Components\Auth\Infrastructure\Service;

use App\Components\Auth\Application\Factory\UserVerificationFactoryInterface;
use App\Components\Auth\Application\Query\UserQueryInterface;
use App\Components\Auth\Application\Query\UserVerificationQueryInterface;
use App\Components\Auth\Application\Repository\UserRepositoryInterface;
use App\Components\Auth\Application\Repository\UserVerificationRepositoryInterface;
use App\Components\Auth\Application\Service\UserVerificationServiceInterface;
use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Auth\Data\Entity\UserVerificationEntity;
use App\Components\Auth\Data\Enums\UserVerificationTypeEnum;
use App\Components\Auth\Domain\DTO\UserVerificationDto;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserVerificationService implements UserVerificationServiceInterface
{
    public function __construct(
        private readonly UserVerificationRepositoryInterface $userVerificationRepository,
        private readonly UserVerificationFactoryInterface $userVerificationFactory,
        private readonly UserVerificationQueryInterface $userVerificationQuery,
        private readonly UserQueryInterface $userQuery,
        private readonly UserRepositoryInterface $userRepository,
        private readonly SmsNotificationService $smsNotificationService
    ) {
    }

    public function addVerificationOtp(string $userUuid, UserVerificationTypeEnum $typeEnum, bool $sendSms = true): UserVerificationDto
    {
        $user = $this->userQuery->findByUuid($userUuid);

        //        if ($sendSms)
//        {
//            try {
//                if (!$user->isSandbox)
//                {
//                    $this->smsNotificationService->sendVerificationMessage($user->phoneNumber, $userVerificationDto->otp());
//                }
//
//            }catch (\Exception $exception){
//                Log::error('[UserVerificationService][addVerificationOtp] '.$exception->getMessage());
//            }
//        }


        return $this->userVerificationFactory->createFromEntity(
            $this->userVerificationRepository->create($userUuid, $typeEnum)
        );
    }

    public function verifyOtp(string $token, string $otp): UserEntity
    {
        $userVerification = $this->userVerificationQuery->findUserVerificationByTokenAndOtp($token, $otp);
        if (!$userVerification) {
            throw new \Exception('Invalid OTP');
        }

        $userVerificationEntity = UserVerificationEntity::findByUuid($userVerification->uuid());
        $userEntity = $userVerificationEntity->user;

        if ($userVerification->Type()->value == UserVerificationTypeEnum::REGISTERATION_OTP->value ||
            $userVerification->Type()->value == UserVerificationTypeEnum::LOGIN_OTP->value) {
            $userEntity->is_active = true;
            $userEntity->phone_verified_at = now();
            $userEntity->save();
        }

        if ($userVerification->Type()->value == UserVerificationTypeEnum::CHANGE_PHONE->value)
        {
            $userEntity->phone_number = $userEntity->temp_phone;
            $userEntity->country_code = $userEntity->temp_country_code;
            $userEntity->temp_phone = null;
            $userEntity->temp_country_code = null;
            $userEntity->save();
        }
        if ($userVerification->Type()->value != UserVerificationTypeEnum::FORGOT_PASSWORD_OTP->value) {
            $userVerificationEntity->delete();
        }

        return $userEntity;
    }

    public function resendOtp(string $token): int
    {

        $updateOtp =  $this->userVerificationRepository->updateByToken($token);
        $userVerification = $this->userVerificationRepository->findByToken($token);
        try {
            $user = $userVerification->user;
            $this->smsNotificationService->sendVerificationMessage($user->phone_number, $userVerification->otp);
        }catch (\Exception $exception){
            Log::error('[UserVerificationService][addVerificationOtp] '.$exception->getMessage());
        }

        return $updateOtp;
    }

    public function verifyEmail(string $token): void
    {
        $userEntity =  $this->userQuery->findUserByEmailVerificationToken($token);
        if (!$userEntity) {
             throw new \Exception('Invalid token');
        }
        $tempEmail = $userEntity->temp_email;
        if ($userEntity->email_verified_at && !$tempEmail) {
               throw new \Exception('Email already verified');
        }
        if ($tempEmail)
        {
            $this->userRepository->update($userEntity->getKey(),[
                'email' => $tempEmail,
                'temp_email' => null,
                'verification_code' => null,
                'email_verified_at' => now(),
            ]);
            return;
        }
        $this->userRepository->update($userEntity->getKey(), [
           'email_verified_at' => now(),
           'verification_code' => null,
        ]);
    }

    public function resendEmailVerification(string $email): void
    {
        $userEntity = $this->userQuery->findUserByEmail($email);
        $userEntity->verification_code = Str::random(32);
        $userEntity->save();

    }
}
