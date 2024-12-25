<?php

namespace App\Libraries\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

final class LatitudeRule implements Rule
{
    /**
     * @param string|int $attribute
     * @param float|mixed $value
     */
    public function passes($attribute, $value): bool
    {
        return is_numeric($value) && $value >= -90 && $value <= 90;
    }

    public function message(): string
    {
        return trans('validation.latitude');
    }
}
