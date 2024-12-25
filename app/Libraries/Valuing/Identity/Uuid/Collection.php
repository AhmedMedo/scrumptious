<?php

namespace App\Libraries\Valuing\Identity\Uuid;

use App\Libraries\Valuing\Identity;
use ArrayIterator;
use InvalidArgumentException;

final class Collection extends ArrayIterator
{
    public function add(Identity\Uuid $uuid): void
    {
        $this->offsetSet($uuid->toString(), $uuid);
    }

    public function equals(Collection $other): bool
    {
        if ($other instanceof Collection == false) {
            return false;
        }

        foreach ($this->getArrayCopy() as $uuid => $value) {
            try {
                $otherValue = $other->get($uuid);
            } catch (InvalidArgumentException $e) {
                return false;
            }

            if ($value->equals($otherValue) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $uuid
     *
     * @return Identity\Uuid
     * @throws InvalidArgumentException
     *
     */
    public function get(string $uuid): Identity\Uuid
    {
        if ($this->offsetExists($uuid) === false) {
            throw new InvalidArgumentException('Not Found Uuid string: ' . $uuid, $uuid);
        }

        return $this->offsetGet($uuid);
    }
}
