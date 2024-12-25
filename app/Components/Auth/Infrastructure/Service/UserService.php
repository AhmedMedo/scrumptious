<?php

namespace App\Components\Auth\Infrastructure\Service;

use App\Components\Auth\Application\Query\UserQueryInterface;
use App\Components\Auth\Application\Query\UserVerificationQueryInterface;
use App\Components\Auth\Application\Repository\UserOldPasswordRepositoryInterface;
use App\Components\Auth\Application\Repository\UserRepositoryInterface;
use App\Components\Auth\Application\Repository\UserVerificationRepositoryInterface;
use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Application\Service\UserVerificationServiceInterface;
use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Auth\Data\Enums\RoleEnum;
use App\Components\Auth\Data\Enums\UserVerificationTypeEnum;
use App\Components\Auth\Domain\DTO\UserDto;
use App\Components\Auth\Domain\DTO\UserVerificationDto;
use App\Components\Auth\Domain\Enum\UserStatusEnum;
use App\Components\Auth\Domain\Exception\AccountInactiveException;
use App\Components\Auth\Domain\Exception\UserNotFoundException;
use App\Components\Auth\Domain\Exception\WrongCredentialsException;
use App\Components\Auth\Domain\Exception\WrongVerificationCodeException;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserQueryInterface $userQuery,
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserVerificationServiceInterface $userVerificationService,
        private readonly UserVerificationQueryInterface $userVerificationQuery,
        private readonly UserVerificationRepositoryInterface $userVerificationRepository,
        private readonly UserOldPasswordRepositoryInterface $userOldPasswordRepository,
    ) {
    }

    /**
     * @throws WrongCredentialsException
     * @throws AccountInactiveException
     * @throws \Exception
     */
    public function login(array $credentials): UserVerificationDto
    {
        $userName = $credentials['username'];
        $isEmailLogin = false;
        if (Str::contains($userName, '@')) {
            $isEmailLogin = true;
            $user = $this->userQuery->findUserByEmail($userName);
        } else {
            $user = $this->userQuery->findUserByPhone($userName);
        }


        if (!Hash::check($credentials['password'], $user->password)) {
            throw new WrongCredentialsException();
        }
//        if (!$user->status !== UserStatusEnum::ACTIVE->value) {
//            throw new AccountInactiveException();
//        }

        //        try {
//            Mail::to($user->email)->send(new \App\Mail\SendVerificationEmail($userVerificationDto->otp(), $user->full_name));
//        } catch (\Exception $e) {
//            throw new \Exception($e->getMessage());
//        }

        return $this->userVerificationService->addVerificationOtp($user->getKey(), UserVerificationTypeEnum::LOGIN_OTP, $isEmailLogin);
    }

    public function user(): UserDto
    {
        return $this->userQuery->findByUuid(auth()->user()->getKey());
    }


    public function register(array $data, RoleEnum $roleEnum = RoleEnum::USER): UserVerificationDto
    {

        $user = $this->userRepository->create($data);
        $user->assignRole($roleEnum->value);
//        try {
//            Mail::to($user->email)->send(new \App\Mail\SendEmailVerificationLink($user->verification_code, $user->full_name));
//        } catch (\Exception $e) {
//            throw new \Exception($e->getMessage());
//        }

        //Add password history
        return $this->userVerificationService->addVerificationOtp($user->getKey(), UserVerificationTypeEnum::REGISTERATION_OTP);
    }

    /**
     * @throws WrongVerificationCodeException
     */
    public function verify(string $code): bool
    {
        $user = $this->userQuery->findUserByVerificationCode($code);


        return $this->userRepository->update($user->getKey(), [
            'verification_code' => null,
            'email_verified_at' => now(),
        ]);
    }

    public function forgetPassword(string $username): UserVerificationDto
    {
        if (Str::contains($username, '@')) {
            $user = $this->userQuery->findUserByEmail($username);
        } else {
            $user = $this->userQuery->findUserByPhone($username);
        }

        return $this->userVerificationService->addVerificationOtp($user->getKey(), UserVerificationTypeEnum::FORGOT_PASSWORD_OTP);
    }

    public function resetPassword(string $token, string $password): bool
    {
        $userVerificationDto = $this->userVerificationQuery->findUserVerificationByToken($token);
        $user = UserEntity::where('uuid', $userVerificationDto->UserUuid())->first();
        $this->userVerificationRepository->delete($userVerificationDto->userUuid(), $userVerificationDto->Type());
        $this->userOldPasswordRepository->create($user->getKey(), $password);
        return $this->userRepository->update($user->getKey(), [
            'password' => Hash::make($password),
        ]);
    }

    public function logout(): bool
    {
        return auth()->user()->token()->revoke();
    }

    public function resendOtp(string $token): int
    {
        return $this->userVerificationService->resendOtp($token);
    }

    public function updateProfile(string $userUuid, array $data, ?string $imagePath): UserDto
    {
        $this->userRepository->update(
            $userUuid,
            $data,
            $imagePath
        );
        return  $this->userQuery->findByUuid($userUuid);
    }

    public function updatePassword(string $userUuid, string $oldPassword, string $password): bool
    {
        $user = $this->userQuery->findUserEntityByUuid($userUuid);
        if (!Hash::check($oldPassword, $user->password)) {
            throw new WrongCredentialsException();
        }

        $this->userOldPasswordRepository->create($user->getKey(), $password);

        return $this->userRepository->update($user->getKey(), [
            'password' => Hash::make($password),
        ]);
    }

    public function deleteAccount(string $userUuid): bool
    {
        return $this->userRepository->delete($userUuid);
    }

    public function loginAsGuest(string $name, string $email, string $phone): UserVerificationDto | bool
    {

        try {
            $user = $this->userRepository->create([
                'first_name' => $name,
                'last_name' => 'Guest',
                'email' => 'guest_' . uniqid() . '@example.com',
                'phone_number' => $phone,
                'password' => Hash::make(Str::random(8)),
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'is_active' => 1,
            ]);
            $user->assignRole(RoleEnum::GUEST->value);
            return $this->userVerificationService->addVerificationOtp($user->getKey(), UserVerificationTypeEnum::LOGIN_OTP);


        }catch (\Exception $exception)
        {
            return false;
        }


    }

    /**
     * @throws \Exception
     */
    public function changeUserPhoneByEmail(string $email, string $countryCode, string $phoneNumber): UserVerificationDto
    {
       $user = $this->userQuery->findUserByEmail($email);
       //check if there is user with same phone
       if ($this->userQuery->userWithPhoneExists($countryCode . $phoneNumber)) {
           throw new \Exception('Phone number already exists');
       }
       $user->phone_number = $countryCode . $phoneNumber;
       $user->country_code = $countryCode;
       $user->save();

       //TODO send SMS
       return $this->userVerificationService->addVerificationOtp($user->getKey(), UserVerificationTypeEnum::LOGIN_OTP);
    }

    public function changeEmail(string $userUuid, string $email)
    {
        $user = $this->userQuery->findUserEntityByUuid($userUuid);
        $this->userRepository->update($user->getKey(), [
            'temp_email' => $email,
            'verification_code'=> Str::random(32),
        ]);

        $user = $this->userQuery->findUserEntityByUuid($userUuid);

    }

    public function changePhone(string $userUuid, string $countryCode, string $phoneNumber): UserVerificationDto
    {
        $user = $this->userQuery->findUserEntityByUuid($userUuid);
        //check if there is user with same phone
        if ($this->userQuery->userWithPhoneExists($countryCode . $phoneNumber)) {
            throw new \Exception('Phone number already exists');
        }

        $user->temp_phone = $countryCode . $phoneNumber;
        $user->temp_country_code = $countryCode;
        $user->save();

        return $this->userVerificationService->addVerificationOtp($user->getKey(), UserVerificationTypeEnum::CHANGE_PHONE);

    }
}
