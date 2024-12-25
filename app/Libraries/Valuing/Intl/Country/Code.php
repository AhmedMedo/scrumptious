<?php

namespace App\Libraries\Valuing\Intl\Country;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Symfony\Component\Intl\Countries;
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
        Assertion::regex($code, '/^[a-zA-Z]{2}$/', 'Invalid Country code: ' . $code);
        Assertion::keyExists(Countries::getNames(), mb_strtoupper($code), 'Invalid Country code: ' . $code);
    }
}
