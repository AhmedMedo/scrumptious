<?php

namespace App\Libraries\Valuing\Number;

use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

final class Quantity extends VO
{
    /**
     * @param int $quantity
     *
     * @return Quantity
     * @throws InvalidArgumentException
     *
     */
    public static function fromNumber(int $quantity): Quantity
    {
        return new self($quantity);
    }

    /**
     * @inheritdoc
     */
    protected function guard($value): void
    {
        Assertion::integer($value, 'Invalid Quantity value: ' . $value);
    }
}
