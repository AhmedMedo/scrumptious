<?php

namespace App\Libraries\Money;

use Illuminate\Support\Str;

final class Currency
{
    /**
     * There exists currencies with different precision
     * but those are extremely uncommon
     *
     * Full list:
     * https://pl.wikipedia.org/wiki/Jen
     * https://pl.wikipedia.org/wiki/Funt_cypryjski
     * https://pl.wikipedia.org/wiki/Dinar_iracki
     * https://pl.wikipedia.org/wiki/Dinar_jordański
     * https://pl.wikipedia.org/wiki/Dinar_kuwejcki
     * https://pl.wikipedia.org/wiki/Dinar_Bahrajnu
     */
    private const PRECISION = 2;

    private string $symbol;

    public function __construct(string $symbol)
    {
        $this->symbol = Str::upper($symbol);
    }

    public function getPrecision(): int
    {
        return self::PRECISION;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function isEqual(Currency $currency): bool
    {
        return $this->getSymbol() === $currency->getSymbol();
    }

    public function __toString()
    {
        return $this->getSymbol();
    }
}
