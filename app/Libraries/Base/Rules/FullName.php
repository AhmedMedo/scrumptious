<?php

namespace App\Libraries\Base\Rules;

class FullName extends Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string|mixed $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return preg_match('/^[a-zA-Z]+(?:\s[a-zA-Z]+)+$/', $value) === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->getLocalizedErrorMessage(
            'full_name',
            'Provided :attribute is invalid'
        );
    }
}
