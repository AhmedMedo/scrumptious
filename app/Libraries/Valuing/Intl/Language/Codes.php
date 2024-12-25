<?php

namespace App\Libraries\Valuing\Intl\Language;

use App\Libraries\Valuing\Char\Text;
use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

/**
 * @property Collection $value
 */
final class Codes extends VO
{
    /**
     * @param array $locales
     *
     * @return Codes
     * @throws InvalidArgumentException
     *
     */
    public static function fromArray(array $locales): Codes
    {
        return new self($locales);
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
        return array_keys($this->value->getArrayCopy());
    }

    /**
     * @inheritdoc
     */
    protected function guard($value): void
    {
        Assertion::isArray($value, 'Invalid Language locales array');
    }

    /**
     * @inheritdoc
     *
     * @throws InvalidArgumentException
     */
    protected function setValue($locales): void
    {
        $this->value = new Collection();

        foreach ($locales as $locale) {
            $this->addCode($locale);
        }
    }

    /**
     * @param string $locale
     *
     * @throws InvalidArgumentException
     */
    public function addCode(string $locale): void
    {
        $this->value->add(Code::fromCode($locale), Text::fromString(''));
    }
}
