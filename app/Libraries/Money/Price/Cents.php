<?php

namespace App\Libraries\Money\Price;

use App\Libraries\Money\Currency;
use App\Libraries\Money\Price;
use App\Libraries\Money\Tax;

class Cents
{
    private Price $price;

    private function __construct(Price $price)
    {
        $this->price = $price;
    }

    public static function fromPrice(Price $price): Cents
    {
        return new self($price);
    }

    public static function build(string $currencySymbol, int $nett = 0, int $gross = 0): Price
    {
        $currency = new Currency($currencySymbol);
        $precision = bcpow(10, $currency->getPrecision());
        $calculatedNett = bcdiv($nett, $precision, $currency->getPrecision());
        $calculatedGross = bcdiv($gross, $precision, $currency->getPrecision());

        return Price::build($currencySymbol, $calculatedNett, $calculatedGross);
    }

    public static function buildEmpty(string $currencySymbol): Price
    {
        return Price::buildEmpty($currencySymbol);
    }

    /**
     * @param string $currencySymbol
     * @param int $nett
     * @param int $taxValue
     * @deprecated Since 07/2022 use buildByNettValue()
     *
     */
    public static function buildByNett(string $currencySymbol, int $nett, int $taxValue): Price
    {
        return self::build($currencySymbol, $nett, Tax::forValue($taxValue)->calculateGrossInCents($nett));
    }

    /**
     * @param string $currencySymbol
     * @param int $gross
     * @param int $taxValue
     * @deprecated Since 07/2022 use buildByGrossValue()
     *
     */
    public static function buildByGross(string $currencySymbol, int $gross, int $taxValue): Price
    {
        return self::build($currencySymbol, Tax::forValue($taxValue)->calculateNettInCents($gross), $gross);
    }

    public static function buildByNettValue(int $nett, int $taxValue = null, string $currencySymbol = null): Price
    {
        $taxValue = $taxValue ?: config('giftit.vat');
        $currencySymbol = $currencySymbol ?: config('giftit.payments.default_currency');

        return self::build(
            $currencySymbol,
            $nett,
            Tax::forValue($taxValue)->calculateGrossInCents($nett)
        );
    }

    public static function buildByGrossValue(int $gross, int $taxValue = null, string $currencySymbol = null): Price
    {
        $taxValue = $taxValue ?: config('giftit.vat');
        $currencySymbol = $currencySymbol ?: config('giftit.payments.default_currency');

        return self::build(
            $currencySymbol,
            Tax::forValue($taxValue)->calculateNettInCents($gross),
            $gross
        );
    }

    public static function buildByData(array $data): Price
    {
        return Price::buildByData($data);
    }

    public function getTaxDiff(): int
    {
        return max(0, $this->getGross() - $this->getNett());
    }

    public function getGross(): int
    {
        $precision = bcpow(10, $this->price->getCurrency()->getPrecision());

        return (int) bcmul($this->price->getGross(), $precision);
    }

    public function getNett(): int
    {
        $precision = bcpow(10, $this->price->getCurrency()->getPrecision());

        return (int) bcmul($this->price->getNett(), $precision);
    }

    public function isLowerThan(Price $price): bool
    {
        return $this->price->isLowerThan($price);
    }

    public function isEqLowerThan(Price $price): bool
    {
        return $this->price->isEqLowerThan($price);
    }

    public function isGreaterThan(Price $price): bool
    {
        return $this->price->isGreaterThan($price);
    }

    public function isEqGreaterThan(Price $price): bool
    {
        return $this->price->isEqGreaterThan($price);
    }

    public function isEqual(Price $price): bool
    {
        return $this->price->isEqual($price);
    }

    public function isEmpty(): bool
    {
        return $this->price->isEmpty();
    }

    public function add(Price $priceToAdd): Price
    {
        return $this->price->add($priceToAdd);
    }

    public function subtract(Price $priceToSubtract): Price
    {
        return $this->price->subtract($priceToSubtract);
    }

    /**
     * @return array{currency_symbol: string, nett: string, gross: string, tax: int}
     */
    public function toArray(): array
    {
        return $this->price->toArray();
    }

    public function toPrice(): Price
    {
        return $this->price;
    }
}
