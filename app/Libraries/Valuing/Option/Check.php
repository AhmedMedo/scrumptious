<?php

namespace App\Libraries\Valuing\Option;

use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

final class Check extends VO
{
    /**
     * @param bool $check
     *
     * @return Check
     * @throws InvalidArgumentException
     *
     */
    public static function fromBoolean(bool $check): Check
    {
        return new self($check);
    }

    /**
     * @inheritdoc
     */
    public function toString(): string
    {
        return $this->value ? '1' : '0';
    }

    /**
     * @inheritdoc
     */
    protected function guard($value): void
    {
        Assertion::boolean($value, 'Invalid Value check: ' . $value);
    }
}
