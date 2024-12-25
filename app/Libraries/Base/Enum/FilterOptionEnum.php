<?php

namespace App\Libraries\Base\Enum;

use Illuminate\Support\Str;
use MyCLabs\Enum\Enum;

/**
 * @method static FilterOptionEnum ALL()
 * @method static FilterOptionEnum ANY()
 * @method static FilterOptionEnum TRUE()
 * @method static FilterOptionEnum FALSE()
 */
class FilterOptionEnum extends Enum
{
    protected const ANY = 'any';
    protected const ALL = 'all';

    protected const FALSE = 'false';
    protected const ZERO = '0';
    protected const OFF = 'off';

    protected const TRUE = 'true';
    protected const ONE = '1';
    protected const ON = 'on';

    /**
     * Check if filter option allows anything.
     *
     * @param string $value
     * @param bool $strict
     *
     * @return bool
     */
    public static function isAnythingAllowed(string $value, bool $strict = false): bool
    {
        if (! $strict) {
            $value = Str::lower($value);
        }

        return in_array(
            Str::lower($value),
            [self::ALL, self::ANY],
            $strict
        );
    }

    public static function isNegative(string $value): bool
    {
        return ! self::isPositive($value);
    }

    public static function isPositive(string $value): bool
    {
        return (bool) filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    public static function isBooleanValue(string $candidate): bool
    {
        return in_array($candidate, [
            self::ONE,
            self::ZERO,
            self::TRUE,
            self::FALSE,
            self::ON,
            self::OFF,
        ], true);
    }
}
