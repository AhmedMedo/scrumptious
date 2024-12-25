<?php

namespace App\Libraries\Money\Discount;

use App\Libraries\Money\Discount;
use App\Libraries\Money\DiscountEnum;
use App\Libraries\Money\Price;

class Cash extends Discount
{
    /**
     * @inheritDoc
     */
    public function apply(Price $price): Price
    {
        return $price->subtract($this->priceToSubtract($price));
    }

    private function priceToSubtract(Price $basePrice): Price
    {
        return Price\Cents::buildByGross($basePrice->getCurrencySymbol(), $this->value, $basePrice->getTaxRate());
    }

    /**
     * @inheritDoc
     */
    protected function initType(): DiscountEnum
    {
        return DiscountEnum::CASH();
    }
}
