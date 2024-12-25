<?php

namespace App\Libraries\Valuing\Char;

use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

final class OneChar extends VO implements Char
{
    /**
     * @param string $char
     *
     * @throws InvalidArgumentException
     *
     * @return OneChar
     */
    public static function fromString(string $char): OneChar
    {
        return new self($char);
    }

    /**
     * @inheritdoc
     */
    protected function guard($value): void
    {
        Assertion::string($value, 'Invalid OneChar string: ' . $value);
        Assertion::maxLength(
            $value,
            $this->maxLength(),
            sprintf('Invalid OneChar string length (%d)', $this->maxLength())
        );
    }

    protected function maxLength(): int
    {
        return 1;
    }
}
