<?php

namespace App\Libraries\Valuing\Support;

use Carbon\Carbon;

trait CreatesDateFromData
{
    private function createDateFromData(array $data): Carbon
    {
        $micro = $data['micro'] ?? 0;

        return Carbon::createFromTimestamp($data['timestamp'], config('app.timezone'))->setMicro($micro);
    }
}
