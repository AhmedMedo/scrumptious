<?php

namespace App\Libraries\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

final class LongitudeRule implements Rule
{
    /**
     * @param string|int $attribute
     * @param float|mixed $value
     */
    public function passes($attribute, $value): bool
    {
        return is_numeric($value) && $value >= -180 && $value <= 180;
    }

    public function message(): string
    {
        return trans('validation.longitude');
    }
}
