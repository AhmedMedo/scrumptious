<?php

namespace App\Libraries\Valuing\Intl\Currency;

use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

/**
 * @property Collection $value
 */
final class Codes extends VO
{
    /**
     * @param array $codes
     *
     * @return Codes
     * @throws InvalidArgumentException
     *
     */
    public static function fromArray(array $codes): Codes
    {
        return new self($codes);
    }

    /**
     * @param Codes|object $other
     *
     * @return bool
     */
    public function equals(object $other): bool
    {
        if ($other instanceof self === false) {
            return false;
        }

        return $this->value->equals($other->value);
    }

    /**
     * @return int[]|string[]
     */
    public function raw(): array
    {
        return array_keys($this->getCodes());
    }

    /**
     * @return Code[]
     */
    public function getCodes(): array
    {
        return $this->value->getArrayCopy();
    }

    /**
     * @inheritdoc
     */
    protected function guard($value): void
    {
        Assertion::isArray($value, 'Invalid Country codes array');
    }

    /**
     * @inheritdoc
     *
     * @throws InvalidArgumentException
     */
    protected function setValue($codes): void
    {
        $this->value = new Collection();

        foreach ($codes as $code) {
            $this->addCode($code);
        }
    }

    /**
     * @param string $code
     *
     * @throws InvalidArgumentException
     */
    public function addCode(string $code): void
    {
        $this->value->add(Code::fromCode($code));
    }
}
