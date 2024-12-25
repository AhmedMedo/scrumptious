<?php

// PRINTERS

// prints hidden html with name of file + line from which printer was called
// use only in printers ^^ vv
use Carbon\Carbon;
use Faker\Factory;
use Propaganistas\LaravelPhone\PhoneNumber;

function printCallLine(): void
{
    $dbg = debug_backtrace();
    $callPlace = $dbg[1];
    $line = isset($callPlace['line']) ? $callPlace['line'] : '??';
    $file = isset($callPlace['file']) ? $callPlace['file'] : '??';

    printf('<span style="display:none">%s:%d</span>' . "\n", $file, $line);
}

function __vd(...$thing): void
{
    ini_set('xdebug.var_display_max_depth', '-1');
    ini_set('xdebug.var_display_max_children', '-1');
    ini_set('xdebug.var_display_max_data', '-1');

    foreach (func_get_args() as $param) {
        print "<pre>";
        var_dump($param);
        printCallLine();
        print "</pre>";
    }
}

function x($txt): void
{
    echo $txt . "\n";
}

function kill($msg = ""): void
{
    x("KILL: " . $msg);
    dbg();
    die;
}

function dbg(): void
{
    print get_dbg();
}

function get_dbg()
{
    $str = '<pre>';
    foreach (debug_backtrace() as $debugLine) {
        if (isset($debugLine['file']) && isset($debugLine['line'])) {
            $str .= $debugLine['file'] . ':' . $debugLine['line'] . "\n";
        }
    }

    return $str . '</pre>';
}

// FIXES

/**
 * Handles deserialization of UTF-8 Data
 *
 * @param string $serializedData
 *
 * @return mixed
 */
function unserialize_utf8(string $serializedData)
{
    // 5.2.2 Fixed wrong length calculation in unserialize S type (MOPB-29 by Stefan Esser) (Stas) - year 2007 - so wtf
    $retval = @unserialize($serializedData);
    if ($retval === false) {
        return unserialize(preg_replace_callback('!s:(\d+):"(.*?)";!s', 'unserialize_utf8_preg_callback', $serializedData));
    } else {
        return $retval;
    }
}

function unserialize_utf8_preg_callback($matches)
{
    $string = $matches[2];

    return 's:' . strlen($string) . ':"' . $string . '";'; // here must be strlen() not mb_strlen()!
}

function __microtime()
{
    [$usec, $sec] = explode(" ", microtime());

    return (float) $usec + (float) $sec;
}

function __memory_usage()
{
    $size = memory_get_usage(true);
    $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];

    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

function __statistic($message, $start): void
{
    __(sprintf("%s [TIME:%s] [MEMORY_USAGE:%s]", $message, __microtime() - $start, __memory_usage()));
}

function makeNumber(string $phone, ?string $countryCode = ''): PhoneNumber
{
    if (preg_match("/\+?\d{1,4}/", $countryCode, $out)) {
        if (preg_match("/^\+{1}/", $countryCode, $out)) {
            return new PhoneNumber($countryCode . $phone);
        } elseif (preg_match("/^0{1}/", $countryCode, $out)) {
            //Cast to int removes all leading zeros
            return new PhoneNumber('+' . (int) $countryCode . $phone);
        } else {
            return new PhoneNumber('+' . $countryCode . $phone);
        }
    }

    return new PhoneNumber($phone, $countryCode);
}

function form_telgani_checkbox(
    $field,
    $name,
    $label,
    $value = null,
    $cols = "col-lg-12 col-md-12 col-sm-12 col-xs-12",
    $options = []
) {
    $options = array_merge(['class' => 'form-control'], $options);
    $form_field = field_dot_to_form($field);

    return view('frontend.forms.checkbox', compact('cols', 'field', 'label', 'name', 'value', 'options', 'form_field'));
}

/**
 * @param $input
 *
 * @return array
 */
function array_filter_recursive($input): array
{
    foreach ($input as &$value) {
        if (is_array($value)) {
            $value = array_filter_recursive($value);
        }
    }

    return array_filter($input);
}

function oldSelectAjax($key, $default = [])
{
    return session()->has('_old_select_ajax.' . $key) ? session('_old_select_ajax')[$key] : $default;
}

/**
 * @param string $fullPhone
 *
 * @return string[]
 */
function parseNumber(string $fullPhone): array
{
    try {
        $fullPhone = str_replace('.', '', $fullPhone);
        $phone = new PhoneNumber($fullPhone);
        $countryCode = strtolower($phone->getCountry());
        $prefix = config("telgani.country_codes.{$countryCode}") ?: config('telgani.country_codes.sa');
        $phoneWithoutPrefix = str_replace($prefix, '', $fullPhone);

        return [$prefix, $phoneWithoutPrefix];
    } catch (Exception $exception) {
        return [config('telgani.country_codes.sa'), $fullPhone];
    }
}

function convertToEnglishNumbers(string $string): string
{
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    $num = range(0, 9);
    $convertedPersianNums = str_replace($persian, $num, $string);

    return str_replace($arabic, $num, $convertedPersianNums);
}

/**
 * @return array
 */
function officeHoursGenerate(): array
{
    $faker = Factory::create();

    $days = [];
    for ($lp = 0; $lp < 7; $lp++) {
        $time = Carbon::now()->startOfDay();
        $isActive = $faker->boolean;
        $isActiveSecond = $isActive ?: $faker->boolean;
        if ($isActiveSecond) {
            $hoursInFirstHalfOfDay = $faker->numberBetween(8, 16);
            $hoursInSecondHalfOfDay = $faker->numberBetween($hoursInFirstHalfOfDay, 24) - $hoursInFirstHalfOfDay;
        } else {
            $hoursInFirstHalfOfDay = $faker->numberBetween(6, 23);
            $hoursInSecondHalfOfDay = $faker->numberBetween(1, 23); //Not important
        }
        $from = $faker->numberBetween(0, ($hoursInFirstHalfOfDay * 60) - 60); //-60 so always have 1 hour to spare,
        $to = $faker->numberBetween($from, ($hoursInFirstHalfOfDay * 60) - $from);

        $fromSecond = $faker->numberBetween(
            $to + 60,
            ($hoursInSecondHalfOfDay * 60) - 60
        ); //-60 so always have 1 hour to spare,
        $toSecond = $faker->numberBetween($fromSecond, ($hoursInSecondHalfOfDay * 60) - $fromSecond) + $fromSecond;

        $days[] = [
            "isActive"       => $isActive,
            "timeFrom"       => $time->copy()->addMinutes($from)->format('H:i'),
            "timeTill"       => $time->copy()->addMinutes($to)->format('H:i'),
            "isActiveSecond" => $isActiveSecond,
            "timeFromSecond" => $time->copy()->addMinutes($fromSecond)->format('H:i'),
            "timeTillSecond" => $time->copy()->addMinutes($toSecond)->format('H:i'),
        ];
    }

    return $days;
}

function uuid2bin($uuid)
{
    return hex2bin(str_replace('-', '', $uuid));
}

function bin2uuid($value): ?string
{
    $string = bin2hex($value);

    return preg_replace('/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/', '$1-$2-$3-$4-$5', $string);
}

function sortArray(array $array): array
{
    foreach ($array as $key => $value) {
        $array[$key] = (int) $value;

    }

    usort($array, function ($a, $b) {
        return $a <=> $b;
    });

    return $array;
}
