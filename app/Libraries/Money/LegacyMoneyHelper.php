<?php

namespace App\Libraries\Money;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;

class LegacyMoneyHelper
{
    private static ?LegacyMoneyHelper $instance = null;

    protected ISOCurrencies $isoCurrencies;

    protected Currency $currency;

    /**
     * @param null $currency
     */
    private function __construct($currency = null)
    {
        $this->isoCurrencies = new ISOCurrencies();
        $this->currency = new Currency($currency ?: config('telgani.payments.default_currency'));
    }

    /**
     * @param null $currency
     *
     * @return LegacyMoneyHelper
     */
    public static function getInstance($currency = null): LegacyMoneyHelper
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($currency);
        }

        return self::$instance;
    }

    public static function parseMoney(string $amount): string
    {
        $amount = str_replace(' ', '', trim($amount));
        $amount = str_replace(',', '.', trim($amount));
        $money = explode(".", $amount);
        $fractions = array_key_exists(1, $money) ? str_pad($money[1], 2, "0") : "00";

        return (int) $money[0] . $fractions;
    }

    /**
     * @param string $amount
     *
     * @return int|null
     */
    public static function parseMoneyIfAmount(string $amount): ?string
    {
        if (is_numeric($amount)) {
            return self::parseMoney($amount);
        }

        return null;
    }

    public function getDecimalFormatted(string $amount, bool $stripFractions = true): string
    {
        $money = new Money((int) $amount, $this->currency);
        $moneyFormatter = new DecimalMoneyFormatter($this->isoCurrencies);

        if ($stripFractions) {
            $moneyArray = explode('.', $moneyFormatter->format($money));

            if (array_key_exists(1, $moneyArray) && $moneyArray[1] === '00') {
                return $moneyArray[0];
            }
        }

        return $moneyFormatter->format($money);
    }

    /**
     * @param string $amount
     *
     * @return bool|string
     */
    public function getDecimalFormattedForApi(string $amount)
    {
        return substr($amount, 0, -2);
    }
}
