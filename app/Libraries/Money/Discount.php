<?php

namespace App\Libraries\Money;

abstract class Discount
{
    protected float $value;

    private DiscountEnum $type;

    public function __construct(float $value)
    {
        $this->type = $this->initType();
        $this->value = $value;
    }

    abstract protected function initType(): DiscountEnum;

    public function getType(): string
    {
        return $this->type->getValue();
    }

    abstract public function apply(Price $price): Price;

    public function getValue(): float
    {
        return $this->value;
    }
}
