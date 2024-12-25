<?php

namespace App\Libraries\Valuing\Identity;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

final class Id extends VO implements AggregateId
{
    /**
     * @param int $id
     *
     * @return Id
     * @throws InvalidArgumentException
     *
     */
    public static function fromIdentity(int $id): Id
    {
        return new self($id);
    }

    /**
     * @inheritdoc
     */
    protected function guard($id): void
    {
        Assertion::integer($id, 'Invalid Id integer: ' . $id);
        Assertion::greaterThan($id, 0, 'Id should be greater than 0');
    }
}
