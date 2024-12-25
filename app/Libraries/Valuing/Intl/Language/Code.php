<?php

namespace App\Libraries\Valuing\Intl\Language;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Symfony\Component\Intl\Languages;
use Webmozart\Assert\Assert as Assertion;

final class Code extends VO implements AggregateId
{
    /**
     * @param string $code
     *
     * @return Code
     * @throws InvalidArgumentException
     *
     */
    public static function fromCode(string $code): Code
    {
        return new self($code);
    }

    /**
     * @inheritdoc
     */
    protected function guard($code): void
    {
        Assertion::regex($code, '/[a-zA-Z]{2}/', 'Invalid Code string: ' . $code);
        Assertion::keyExists(Languages::getNames(), $code, 'Invalid Code string: ' . $code);
    }
}
