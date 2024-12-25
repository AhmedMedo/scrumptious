<?php

namespace App\Libraries\Valuing\Identity;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Valuing\VO;
use Exception;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

final class Uuid extends VO implements AggregateId
{
    /**
     * @param string $uuid
     *
     * @return Uuid
     * @throws InvalidArgumentException
     *
     */
    public static function fromIdentity(string $uuid): Uuid
    {
        return new self($uuid);
    }

    public static function validate(string $uuid): bool
    {
        try {
            self::fromIdentity($uuid);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    protected function guard($uuid): void
    {
        Assertion::uuid($uuid, 'Invalid Uuid string: ' . $uuid);
    }
}
