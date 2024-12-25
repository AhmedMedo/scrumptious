<?php

namespace App\Libraries\Money;

use App\Libraries\Money\Validator\MoneyValidator;
use LogicException;
use Webmozart\Assert\Assert as Assertion;

use function sprintf;

final class Price
{
    private Money $nett;

    private Money $gross;

    private Currency $currency;

    private Tax $tax;

    private bool $canBeNegative;

    private function __construct(
        string $currencySymbol,
        float $nett = 0.0,
        float $gross = 0.0,
        bool $canBeNegative = false
    ) {
        $this->tax = Tax::build($nett, $gross);
        $this->currency = new Currency($currencySymbol);
        $this->canBeNegative = $canBeNegative;

        $this->nett = new Money($nett, $canBeNegative);
        $this->gross = new Money($gross, $canBeNegative);
        $this->assertValidMoney();
    }

    public static function build(
        string $currencySymbol,
        float $nett = 0.0,
        float $gross = 0.0,
        bool $canBeNegative = false
    ): Price {
        return new self(
            $currencySymbol,
            $nett,
            $gross,
            $canBeNegative
        );
    }

    /**
     * @deprecated Since 07/2023 use buildByGrossValue()
     */
    public static function buildByNett(
        string $currencySymbol,
        float $nett,
        int $taxValue,
        bool $canBeNegative = false
    ): Price {
        return new self(
            $currencySymbol,
            $nett,
            Tax::forValue($taxValue)->calculateGross($nett),
            $canBeNegative
        );
    }

    /**
     * @deprecated Since 07/2022 use buildByGrossValue()
     */
    public static function buildByGross(
        string $currencySymbol,
        float $gross,
        int $taxValue,
        bool $canBeNegative = false
    ): Price {
        return new self(
            $currencySymbol,
            Tax::forValue($taxValue)->calculateNett($gross),
            $gross,
            $canBeNegative
        );
    }

    /**
     * @deprecated Since 07/2023 use buildByGrossValue().
     *             Don't use buildByNettValue() unless it's absolutely necessary. This is only allowed if you don't have
     *             gross price, and you need to calculate it based on nett price and tax rate.
     */
    public static function buildByNettValue(
        float $nettValue,
        int $taxValue = null,
        string $currencySymbol = null,
        bool $canBeNegative = false
    ): Price {
        $taxValue = $taxValue ?: config('giftit.vat');
        $currencySymbol = $currencySymbol ?: config('giftit.payments.default_currency');

        return new self(
            $currencySymbol,
            $nettValue,
            Tax::forValue($taxValue)->calculateGross($nettValue),
            $canBeNegative
        );
    }

    public static function buildByGrossValue(
        float $grossValue,
        int $taxValue = null,
        string $currencySymbol = null,
        bool $canBeNegative = false
    ): Price {
        $taxValue = $taxValue ?: config('giftit.vat');
        $currencySymbol = $currencySymbol ?: config('giftit.payments.default_currency');

        return new self(
            $currencySymbol,
            Tax::forValue($taxValue)->calculateNett($grossValue),
            $grossValue,
            $canBeNegative
        );
    }

    public static function buildByData(array $data): Price
    {
        Assertion::keyExists($data, 'currency_symbol', 'Expected the key %s to exist in price data.');
        Assertion::keyExists($data, 'gross', 'Expected the key %s to exist in price data.');
        Assertion::keyExists($data, 'tax', 'Expected the key %s to exist in price data.');
        $canBeNegative = $data['negative'] ?? false;

        return self::buildByGross(
            $data['currency_symbol'],
            $data['gross'],
            $data['tax'],
            $canBeNegative
        );
    }

    public static function buildEmpty(string $currencySymbol = null): Price
    {
        return new self(
            $currencySymbol ?: config('giftit.payments.default_currency'),
            0.0,
            0.0
        );
    }

    public function toCents(): Price\Cents
    {
        return Price\Cents::fromPrice($this);
    }

    public function getTaxDiff(): float
    {
        return round(max(0.0, $this->getGross() - $this->getNett()), $this->currency->getPrecision());
    }

    public function getMoneyTaxDiffFormat(string $format = '%.2f'): string
    {
        return PriceHelper::getMoneyFormat($this->getTaxDiff(), $format);
    }

    public function getNumberTaxDiffFormat(): string
    {
        return number_format($this->getTaxDiff(), $this->currency->getPrecision());
    }

    public function getGross(): float
    {
        return round($this->gross->getValue(), $this->currency->getPrecision());
    }

    public function getMoneyGrossFormat(string $format = '%.2f'): string
    {
        return PriceHelper::getMoneyFormat($this->getGross(), $format);
    }

    public function getNumberGrossFormat(): string
    {
        return number_format($this->getGross(), $this->currency->getPrecision());
    }

    public function getNett(): float
    {
        return round($this->nett->getValue(), $this->currency->getPrecision());
    }

    public function getMoneyNettFormat(string $format = '%.2f'): string
    {
        return PriceHelper::getMoneyFormat($this->getNett(), $format);
    }

    public function getNumberNettFormat(): string
    {
        return number_format($this->getNett(), $this->currency->getPrecision());
    }

    public function isLowerThan(Price $price): bool
    {
        return $this->getGross() < $price->getGross();
    }

    public function isEqLowerThan(Price $price): bool
    {
        return $this->getGross() <= $price->getGross();
    }

    public function isEqGreaterThan(Price $price): bool
    {
        return $this->getGross() >= $price->getGross();
    }

    public function isEqual(Price $price): bool
    {
        if ($this->compareCurrencies($this, $price) === false) {
            return false;
        }

        $isGrossEqual = MoneyValidator::areCentsEqual($this->toCents()->getGross(), $price->toCents()->getGross());
        $isNettEqual = MoneyValidator::areCentsEqual($this->toCents()->getNett(), $price->toCents()->getNett());

        return $isGrossEqual && $isNettEqual;
    }

    private function compareCurrencies(Price $representative, Price $candidate): bool
    {
        return $representative->getCurrency()->isEqual($candidate->getCurrency());
    }

    public function canBeNegative(): bool
    {
        return $this->canBeNegative;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function add(Price $candidate): Price
    {
        $currency = $this->buildCurrency($this, $candidate);
        $calculatedGross = (int) bcadd($this->toCents()->getGross(), $candidate->toCents()->getGross());
        $calculatedNett = (int) bcadd($this->toCents()->getNett(), $candidate->toCents()->getNett());

        return Price\Cents::build($currency, $calculatedNett, $calculatedGross);
    }

    public function subtract(Price $priceToSubtract): Price
    {
        $currency = $this->buildCurrency($this, $priceToSubtract);

        if ($this->isGreaterThan($priceToSubtract)) {
            $calculatedGross = (int) bcsub($this->toCents()->getGross(), $priceToSubtract->toCents()->getGross());
            $calculatedNett = (int) bcsub($this->toCents()->getNett(), $priceToSubtract->toCents()->getNett());

            return Price\Cents::build($currency, $calculatedNett, $calculatedGross);
        }

        return self::buildEmpty($currency);
    }

    public function multiply(float $times): Price
    {
        if ($times < 0) {
            throw new LogicException('Multiply param must greater than 0');
        }

        $currency = (string) $this->getCurrency();
        $calculatedNett = (int) bcmul($this->toCents()->getNett(), $times);
        $calculatedGross = (int) bcmul($this->toCents()->getGross(), $times);

        return Price\Cents::build($currency, $calculatedNett, $calculatedGross);
    }

    public function divide(int $times): Price
    {
        if ($times <= 0) {
            throw new LogicException('Divide factor must be positive and greater than zero');
        }

        $currency = (string) $this->getCurrency();
        $calculatedNett = (int) bcdiv($this->toCents()->getNett(), $times);
        $calculatedGross = (int) bcdiv($this->toCents()->getGross(), $times);

        return Price\Cents::build($currency, $calculatedNett, $calculatedGross);
    }

    private function buildCurrency(Price $representative, Price $candidate): Currency
    {
        $this->checkCurrencies($representative->getCurrency(), $candidate->getCurrency());

        return $representative->getCurrency();
    }

    private function checkCurrencies(Currency $representative, Currency $candidate): void
    {
        if ($representative->isEqual($candidate) === false) {
            $message = sprintf(
                'Can not operate on different currencies ("%s" and "%s")',
                (string) $representative,
                (string) $candidate
            );

            throw new LogicException($message);
        }
    }

    public function isGreaterThan(Price $price): bool
    {
        return ($this->getGross() > $price->getGross()) && ($this->getNett() > $price->getNett());
    }

    public function isEmpty(): bool
    {
        return $this->gross->getValue() === 0.0 && $this->nett->getValue() === 0.0;
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    public function getTaxRate(): int
    {
        return $this->getTax()->getValue();
    }

    public function getTaxRateFormat(): string
    {
        return (string) $this->getTaxRate();
    }

    public function subtractFrom(Price $priceToSubtract): Price
    {
        $currency = $this->buildCurrency($this, $priceToSubtract);

        if ($this->isGreaterThan($priceToSubtract)) {
            $calculatedGross = (int) bcsub($this->toCents()->getGross(), $priceToSubtract->toCents()->getGross());

            return Price\Cents::buildByGrossValue($calculatedGross, $this->getTaxRate(), $currency->getSymbol());
        }

        return self::buildEmpty($currency);
    }

    public function multiplyBy(float $times): Price
    {
        if ($times < 0) {
            throw new LogicException('Multiply param must greater than 0');
        }

        $calculatedGross = (int) bcmul($this->toCents()->getGross(), $times);

        return Price\Cents::buildByGrossValue($calculatedGross, $this->getTaxRate(), $this->getCurrency()->getSymbol());
    }

    private function getTax(): Tax
    {
        return $this->tax;
    }

    /**
     * @return array{
     *     currency_symbol: string,
     *     nett: string,
     *     gross: string,
     *     tax: int
     * }
     */
    public function toArray(): array
    {
        return [
            'currency_symbol' => $this->getCurrencySymbol(),
            'nett'            => $this->getMoneyNettFormat(),
            'gross'           => $this->getMoneyGrossFormat(),
            'tax'             => $this->getTaxRate(),
        ];
    }

    public function getCurrencySymbol(): string
    {
        return (string) $this->currency;
    }

    private function assertValidMoney(): void
    {
        $grossIsNegative = $this->gross->isNegative();
        $nettIsNegative = $this->nett->isNegative();
        $moneyIsNegative = ($grossIsNegative || $nettIsNegative);

        if ($grossIsNegative && ! $this->canBeNegative) {
            throw new LogicException('Gross cannot be negative.');
        }

        if ($nettIsNegative && ! $this->canBeNegative) {
            throw new LogicException('Nett cannot be negative.');
        }

        if ($this->canBeNegative && $moneyIsNegative) {
            if ($this->gross->isGreaterThan($this->nett)) {
                throw new LogicException('Nett cannot be greater than gross.');
            }

            return;
        }

        if ($this->nett->isGreaterThan($this->gross)) {
            throw new LogicException('Nett cannot be greater than gross.');
        }
    }
}
