<?php

namespace App\Libraries\Valuing\Intl\Currency;

use ArrayIterator;
use InvalidArgumentException;

final class Collection extends ArrayIterator
{
    public function add(Code $code): void
    {
        $this->offsetSet($code->toString(), $code);
    }

    public function equals(Collection $other): bool
    {
        if ($other instanceof self === false) {
            return false;
        }

        /** @var Code $code */
        foreach ($this->getArrayCopy() as $code) {
            try {
                $otherValue = $other->get($code->toString());
            } catch (InvalidArgumentException $e) {
                return false;
            }

            if ($code->equals($otherValue) === false) {
                return false;
            }
        }

        return true;
    }

    public function get(string $code): Code
    {
        if ($this->offsetExists($code) === false) {
            throw new InvalidArgumentException('Not found Currency code: ' . $code, $code);
        }

        return $this->offsetGet($code);
    }
}
