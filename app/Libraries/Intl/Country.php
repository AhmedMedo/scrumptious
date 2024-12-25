<?php

namespace App\Libraries\Intl;

class Country
{
    private string $code;

    private Currency $currency;

    private Language $language;

    private Vat $vat;

    public function __construct(string $code, Currency $currency, Language $language, Vat $vat)
    {
        $this->code = $code;
        $this->currency = $currency;
        $this->language = $language;
        $this->vat = $vat;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function currency(): string
    {
        return $this->currency->code();
    }

    public function locale(): string
    {
        return $this->language->locale();
    }

    public function vatRate(): int
    {
        return $this->vat->rate();
    }
}
