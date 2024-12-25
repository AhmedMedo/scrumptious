<?php

namespace App\Libraries\Valuing\Intl\Currency;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Symfony\Component\Intl\Currencies;
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
        Assertion::regex($code, '/^[a-zA-Z]{3}$/', 'Invalid Currency code: ' . $code);
        Assertion::keyExists(Currencies::getNames(), mb_strtoupper($code), 'Invalid Currency code: ' . $code);
    }
}
