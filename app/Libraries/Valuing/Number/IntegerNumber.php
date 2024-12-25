<?php

namespace App\Libraries\Valuing\Number;

use App\Libraries\Valuing\VO;
use Webmozart\Assert\Assert;

class IntegerNumber extends VO
{
    public static function fromInteger(int $number): self
    {
        return new IntegerNumber($number);
    }

    /** @param mixed $value */
    protected function guard($value): void
    {
        Assert::integer($value, 'Value is not an integer.');
    }
}
