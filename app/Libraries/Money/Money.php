<?php

namespace App\Libraries\Money;

use LogicException;

final class Money
{
    private float $value;

    public function __construct(float $value, bool $canBeNegative = false)
    {
        if ($value < 0 && ! $canBeNegative) {
            throw new LogicException('Money value must be positive');
        }

        $this->value = round($value, 2);
    }

    public function isGreaterThan(Money $money): bool
    {
        //floating point calculations precision problem here
        $currentValue = $this->roundValue($this);
        $candidateValue = $this->roundValue($money);

        return $currentValue > $candidateValue;
    }

    public function isLowerThan(Money $money): bool
    {
        //floating point calculations precision problem here
        $currentValue = $this->roundValue($this);
        $candidateValue = $this->roundValue($money);

        return $currentValue < $candidateValue;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function isNegative(): bool
    {
        return $this->getValue() < 0;
    }

    private function roundValue(self $money): float
    {
        return round($money->getValue(), 6);
    }
}
