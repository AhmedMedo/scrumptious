<?php

namespace App\Libraries\Valuing\Intl\Language;

use App\Libraries\Valuing\Char\Text;
use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

/**
 * @property Collection $value
 */
final class Texts extends VO
{
    /**
     * @param array $data
     *
     * @return Texts
     * @throws InvalidArgumentException
     *
     */
    public static function fromArray(array $data): Texts
    {
        return new self($data);
    }

    public function getLocale(string $locale): Text
    {
        $text = Text::fromString('');

        if ($this->value->offsetExists($locale) === true) {
            /** @var Text $text */
            $text = $this->value->offsetGet($locale);
        }

        return $text;
    }

    /**
     * @param Texts|object $other
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
     * @return array
     */
    public function raw(): array
    {
        $data = [];

        foreach ($this->getLocales() as $locale => $text) {
            $data[$locale] = $text->toString();
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getLocales(): array
    {
        return $this->value->getArrayCopy();
    }

    /**
     * @inheritdoc
     */
    protected function guard($value): void
    {
        Assertion::isArray($value, 'Invalid Locales array');
    }

    /**
     * @inheritdoc
     *
     * @throws InvalidArgumentException
     */
    protected function setValue($data): void
    {
        $this->value = new Collection();

        foreach ($data as $locale => $text) {
            $this->addLocale($locale, (string) $text);
        }
    }

    /**
     * @param string $locale
     * @param string $text
     *
     * @throws InvalidArgumentException
     */
    public function addLocale(string $locale, string $text): void
    {
        $this->value->add(Code::fromCode($locale), Text::fromString($text));
    }
}
