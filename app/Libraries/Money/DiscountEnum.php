<?php

namespace App\Libraries\Money;

use MyCLabs\Enum\Enum;

/**
 * Class DiscountType
 *
 * @package App\Libraries\Money
 *
 * @method static DiscountEnum CASH()
 * @method static DiscountEnum PERCENTAGE()
 */
class DiscountEnum extends Enum
{
    protected const CASH = 'cash';
    protected const PERCENTAGE = 'percentage';
}
