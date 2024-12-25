<?php

namespace App\Libraries\Validation\Rules;

use App\Component\Account\DomainModel\Auth\Enum\RoleEnum;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Webmozart\Assert\Assert;

class UserHasRole implements Rule
{
    private $userUuid;

    private array $roles;

    public function __construct(array $roles)
    {
        $this->roles = array_map('strval', $roles);
    }

    /**
     * @param string|mixed $attribute
     * @param string|mixed $value
     */
    public function passes($attribute, $value): bool
    {
        $this->userUuid = $value;
        $this->validate();

        return DB::query()
            ->select('user.uuid')
            ->from('user')
            ->join('user_to_role', 'user.uuid', '=', 'user_to_role.user_uuid')
            ->join('user_role', 'user_to_role.role_uuid', '=', 'user_role.uuid')
            ->where('user.uuid', '=', $this->userUuid)
            ->whereIn('user_role.name', $this->roles)
            ->exists();
    }

    public function message(): string
    {
        return sprintf('Selected user is not a %s.', implode(', ', $this->roles));
    }

    private function validate(): void
    {
        Assert::uuid($this->userUuid, 'Invalid user uuid passed.');
        Assert::notEmpty($this->roles, 'Invalid roles passed.');

        foreach ($this->roles as $role) {
            Assert::true(RoleEnum::isValid($role), "Passed role {$role} is invalid.");
        }
    }
}
