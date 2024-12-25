<?php

declare(strict_types=1);

namespace App\Libraries\Base\Rules;

class FlightNumberRule extends Rule
{
    /**
     * @param string|mixed $attribute
     * @param mixed $value
     */
    public function passes($attribute, $value): bool
    {
        return (bool) preg_match('/^[A-Z]{2,3}\s{0,1}[0-9]{3,4}$/', $value);
    }

    public function message(): string
    {
        return $this->getLocalizedErrorMessage(
            'flight_number',
            'Provided :attribute is invalid'
        );
    }
}
