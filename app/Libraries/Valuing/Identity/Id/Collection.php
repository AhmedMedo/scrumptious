<?php

namespace App\Libraries\Valuing\Identity\Id;

use App\Libraries\Valuing\Identity;
use ArrayIterator;
use InvalidArgumentException;

final class Collection extends ArrayIterator
{
    public function add(Identity\Id $id): void
    {
        $this->offsetSet($id->toString(), $id);
    }

    public function equals(Collection $other): bool
    {
        if ($other instanceof Collection == false) {
            return false;
        }

        foreach ($this->getArrayCopy() as $id => $value) {
            try {
                $otherValue = $other->get($id);
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
     * @param int $id
     *
     * @return Identity\Id
     * @throws InvalidArgumentException
     *
     */
    public function get(int $id): Identity\Id
    {
        if ($this->offsetExists($id) === false) {
            throw new InvalidArgumentException('Not Found Id: ' . $id, $id);
        }

        return $this->offsetGet($id);
    }
}
