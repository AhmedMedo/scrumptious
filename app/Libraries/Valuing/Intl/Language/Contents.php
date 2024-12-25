<?php

namespace App\Libraries\Valuing\Intl\Language;

use App\Libraries\Valuing\Char\Content;
use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

/**
 * @property Collection $value
 */
final class Contents extends VO
{
    /**
     * @param array $data
     *
     * @return Contents
     * @throws InvalidArgumentException
     *
     */
    public static function fromArray(array $data): Contents
    {
        return new self($data);
    }

    /**
     * @param string $locale
     *
     * @throws InvalidArgumentException
     */
    public function getLocale(string $locale): Content
    {
        $content = Content::fromString('');

        if ($this->value->offsetExists($locale) === true) {
            /** @var Content $content */
            $content = $this->value->offsetGet($locale);
        }

        return $content;
    }

    /**
     * @param Contents|object $other
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

        foreach ($this->getLocales() as $locale => $content) {
            $data[$locale] = $content->toString();
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

        foreach ($data as $locale => $content) {
            $this->addLocale($locale, (string) $content);
        }
    }

    /**
     * @param string $locale
     * @param string $content
     *
     * @throws InvalidArgumentException
     */
    public function addLocale(string $locale, string $content): void
    {
        $this->value->add(Code::fromCode($locale), Content::fromString($content));
    }
}
