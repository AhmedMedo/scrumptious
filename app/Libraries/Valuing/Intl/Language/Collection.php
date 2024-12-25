<?php

namespace App\Libraries\Valuing\Intl\Language;

use App\Libraries\Valuing\Char;
use ArrayIterator;
use InvalidArgumentException;

final class Collection extends ArrayIterator
{
    public function add(Code $locale, Char\Char $text): void
    {
        $this->offsetSet($locale->toString(), $text);
    }

    public function equals(Collection $other): bool
    {
        if ($other instanceof self === false) {
            return false;
        }

        foreach ($this->getArrayCopy() as $locale => $text) {
            try {
                $otherValue = $other->get($locale);
            } catch (InvalidArgumentException $e) {
                return false;
            }

            if ($text->equals($otherValue) === false) {
                return false;
            }
        }

        return true;
    }

    /** @return mixed */
    public function get(string $locale)
    {
        if ($this->offsetExists($locale) === false) {
            throw new InvalidArgumentException('Not Found Locale string: ' . $locale, $locale);
        }

        return $this->offsetGet($locale);
    }
}
