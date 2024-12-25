<?php

namespace App\Libraries\Valuing\Char;

use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

final class Text extends VO implements Char
{
    /**
     * @param string $text
     *
     * @return Text
     * @throws InvalidArgumentException
     *
     */
    public static function fromString(string $text): Text
    {
        return new self($text);
    }

    /**
     * @inheritdoc
     */
    protected function guard($text): void
    {
        Assertion::string($text, 'Invalid Text string: ' . $text);
        Assertion::maxLength(
            $text,
            $this->maxLength(),
            sprintf('Invalid Text string length (%d)', $this->maxLength())
        );
    }

    private function maxLength(): int
    {
        return 2 ** 8;
    }
}
