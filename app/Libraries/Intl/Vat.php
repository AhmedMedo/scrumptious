<?php

namespace App\Libraries\Intl;

class Vat
{
    private int $rate;

    public function __construct(int $rate)
    {
        $this->rate = $rate;
    }

    public function rate(): int
    {
        return $this->rate;
    }
}
