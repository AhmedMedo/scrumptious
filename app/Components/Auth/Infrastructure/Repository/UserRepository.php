<?php

namespace App\Components\Auth\Infrastructure\Repository;

use App\Components\Auth\Application\Repository\UserRepositoryInterface;
use App\Components\Auth\Data\Entity\UserEntity;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): UserEntity
    {
        return UserEntity::create([
            'first_name' => Arr::get($data, 'first_name'),
            'last_name' => Arr::get($data, 'last_name'),
            'country_uuid' => Arr::has($data, 'country_uuid') ? Arr::get($data, 'country_uuid') : null,
            'phone_number' =>   Arr::get($data, 'country_code', '') . Arr::get($data, 'phone_number', ''),
            'country_code' => Arr::get($data, 'country_code'),
            'email' => Arr::get($data, 'email'),
            'password' => Arr::get($data, 'password'),
            'verification_code' => Str::random(32),
            'address' => Arr::get($data, 'address'),
            'birth_date'        => Arr::get($data, 'birth_date', null),
            'weight'            => Arr::get($data, 'weight', null),
            'weight_unit'       => Arr::get($data, 'weight_unit', null),
            'height'            => Arr::get($data, 'height', null),
            'height_unit'       => Arr::get($data, 'height_unit', null),
            'user_diet'         => Arr::get($data, 'user_diet', null),
            'goal'              => Arr::get($data, 'goal', null),
            'have_allergies'    => Arr::get($data, 'have_allergies', null),
            'allergies'         => Arr::get($data, 'allergies', []),  // assuming allergies is stored as JSON or serialized array
            'gender'            => Arr::get($data, 'gender', null),
        ]);
    }

    /**
     * @throws \Throwable
     */
    public function update(string $userUuid, array $data, ?string $imagePath = null, bool $resetPhoneVerification = true): bool
    {
        $user = UserEntity::where('uuid', $userUuid)->firstOrFail();

        $forceResetPhone = Arr::has($data, 'phone_number') && $user->phone_number != Arr::get($data, 'phone_number') && $resetPhoneVerification;
        if ($forceResetPhone) {
            $data['phone_verified_at'] = null;
        }
        $isUpdated =  $user->updateOrFail($data);
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            $user->addMedia(Storage::disk('public')->path($imagePath))->toMediaCollection('profile');
        }
        return $isUpdated;
    }

    public function delete(string $userUuid): bool
    {
        return UserEntity::where('uuid', $userUuid)->delete();
    }
}
