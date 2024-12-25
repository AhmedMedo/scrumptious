<?php

namespace App\Libraries\Valuing\Date;

use App\Libraries\Valuing\VO;
use Carbon\Carbon;

final class Period extends VO
{
    private ?Carbon $from = null;

    private ?Carbon $until = null;

    /**
     * @param array $from
     * @param array $until
     *
     * @return Period
     */
    public static function fromPeriod(array $from, array $until): Period
    {
        return new self([
            'from'  => $from,
            'until' => $until,
        ]);
    }

    public function from(): ?Carbon
    {
        return $this->from;
    }

    public function until(): ?Carbon
    {
        return $this->until;
    }

    public function inDays(): int
    {
        $dropOff = $this->until->copy();
        $pickup = $this->from->copy();

        $dropOff->startOfDay();
        $pickup->startOfDay();

        return $dropOff->diffInDays($pickup);
    }

    /**
     * @inheritDoc
     */
    protected function guard($value): void
    {
        $this->from = $this->createDateFromData($value['from']);
        $this->until = $this->createDateFromData($value['until']);
    }

    /**
     * @param array $data
     *
     * @return Carbon
     */
    private function createDateFromData(array $data): Carbon
    {
        return Carbon::create(
            $data['year'],
            $data['month'],
            $data['day'],
            $data['hour'],
            $data['minute'],
            $data['second'],
            config('app.timezone')
        );
    }
}
