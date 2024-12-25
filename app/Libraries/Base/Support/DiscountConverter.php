<?php

declare(strict_types=1);

namespace App\Libraries\Base\Support;

class DiscountConverter
{
    public static function toPercentage(float $baseValue, float $discountedValue): float
    {
        $discountValue = $baseValue - $discountedValue;
        return round($discountValue / $baseValue * 100, 2);
    }
}
