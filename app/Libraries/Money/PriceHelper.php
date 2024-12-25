<?php

namespace App\Libraries\Money;

class PriceHelper
{
    public static function getMoneyFormat(float $money, string $format = '%.2f'): string
    {
        return sprintf($format, $money);
    }

    public static function parseMoney(string $amount): string
    {
        $amount = str_replace(' ', '', trim($amount));
        $amount = str_replace(',', '.', trim($amount));
        $money = explode(".", $amount);

        $fractions = array_key_exists(1, $money) ? str_pad($money[1], 2, "0") : "00";

        return (int) $money[0] . $fractions;
    }
}
