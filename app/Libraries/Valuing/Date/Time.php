<?php

namespace App\Libraries\Valuing\Date;

use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

final class Time extends VO
{
    /**
     * @param int $time
     *
     * @return Time
     * @throws InvalidArgumentException
     *
     */
    public static function fromTimestamp(int $time): Time
    {
        return new self($time);
    }

    /**
     * @inheritdoc
     */
    protected function guard($time): void
    {
        Assertion::integer($time, 'Invalid Timestamp value' . $time);
    }
}
