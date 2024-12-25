<?php

namespace App\Libraries\Support;

use Carbon\Carbon;

final class DateFormatter
{
    private static array $months = [
        "Jan" => "يناير",
        "Feb" => "فبراير",
        "Mar" => "مارس",
        "Apr" => "أبريل",
        "May" => "مايو",
        "Jun" => "يونيو",
        "Jul" => "يوليو",
        "Aug" => "أغسطس",
        "Sep" => "سبتمبر",
        "Oct" => "أكتوبر",
        "Nov" => "نوفمبر",
        "Dec" => "ديسمبر",
    ];

    public static function formatWithNumberOfDaysAndTime(
        Carbon $date,
        int $numberOfDays,
        string $locale
    ): string {
        switch ($locale) {
            case 'ar':
                return sprintf(
                    '%s %s (%s %s) %s',
                    $date->format('d'),
                    self::$months[$date->format('M')],
                    $numberOfDays,
                    trans('front_mobile.common.period_days', [], $locale),
                    $date->format('h:i a')
                );
            default:
                return sprintf(
                    '%s (%s %s) %s',
                    $date->format('d M'),
                    $numberOfDays,
                    trans('front_mobile.common.period_days', [], $locale),
                    $date->format('h:i a')
                );
        }
    }

    public static function formatWithNumberOfDays(Carbon $date, int $numberOfDays, string $locale): string
    {
        switch ($locale) {
            case 'ar':
                return sprintf(
                    '%s %s (%s %s)',
                    $date->format('d'),
                    self::$months[$date->format('M')],
                    $numberOfDays,
                    trans('front_mobile.common.period_days', [], $locale)
                );
            default:
                return sprintf(
                    '%s (%s %s)',
                    $date->format('d M'),
                    $numberOfDays,
                    trans('front_mobile.common.period_days', [], $locale)
                );
        }
    }
}
