<?php

namespace App\Libraries\Valuing\Option;

use App\Libraries\Valuing\VO;
use InvalidArgumentException;

abstract class Enum extends VO
{
    /**
     * @inheritdoc
     */
    protected function guard($value): void
    {
        if (in_array($value, $this->validValues(), true) === false) {
            throw new InvalidArgumentException('Invalid Value enum: ' . $value);
        }
    }

    /**
     * @return array
     */
    abstract protected function validValues(): array;
}
