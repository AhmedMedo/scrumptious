<?php

namespace App\Libraries\Valuing\Date;

use App\Libraries\Valuing\Number\Quantity;
use App\Libraries\Valuing\VO;

final class Year extends VO
{
    public static function fromYear(int $year): Year
    {
        return new self($year);
    }

    /**
     * @inheritdoc
     */
    protected function guard($year): void
    {
        $this->setValue(Quantity::fromNumber($year)->raw());
    }
}
