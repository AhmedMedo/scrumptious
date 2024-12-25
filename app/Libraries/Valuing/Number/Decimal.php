<?php

namespace App\Libraries\Valuing\Number;

use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

final class Decimal extends VO
{
    /**
     * @param float $decimal
     *
     * @return Decimal
     * @throws InvalidArgumentException
     *
     */
    public static function fromFloat(float $decimal): Decimal
    {
        return new self($decimal);
    }

    /**
     * @inheritdoc
     */
    protected function guard($value): void
    {
        Assertion::float($value, 'Invalid Decimal value: ' . $value);
    }
}
