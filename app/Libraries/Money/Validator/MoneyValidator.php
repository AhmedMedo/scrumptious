<?php

declare(strict_types=1);

namespace App\Libraries\Money\Validator;

class MoneyValidator
{
    private const MARGIN_OF_ERROR_FOR_CENTS = 3;

    public static function areCentsEqual(int $representative, int $candidate): bool
    {
        if ($representative === $candidate) {
            return true;
        }

        return abs((int) bcsub((string) $representative, (string) $candidate)) <= self::MARGIN_OF_ERROR_FOR_CENTS;
    }
}
