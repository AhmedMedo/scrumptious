<?php

namespace App\Libraries\Intl;

class Language
{
    private string $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function locale(): string
    {
        return $this->locale;
    }
}
