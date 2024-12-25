<?php

namespace App\Libraries\Money\Discount;

use App\Libraries\Money\Discount;
use App\Libraries\Money\DiscountEnum;
use App\Libraries\Money\Price;

final class Percentage extends Discount
{
    /**
     * @inheritDoc
     */
    public function apply(Price $price): Price
    {
        $multiplier = (int) $this->value;
        if ($multiplier === 100) {
            return Price::buildEmpty($price->getCurrencySymbol());
        }

        return $price->multiply(1 - $multiplier / 100);
    }

    /**
     * @inheritDoc
     */
    protected function initType(): DiscountEnum
    {
        return DiscountEnum::PERCENTAGE();
    }

    public static function buildByValue(int $value): Percentage
    {
        return new self((float) $value);
    }
}
