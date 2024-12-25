<?php

namespace App\Libraries\Valuing\Date;

use App\Libraries\Valuing\Support\CreatesDateFromData;
use App\Libraries\Valuing\VO;
use Carbon\Carbon;

final class Date extends VO
{
    use CreatesDateFromData;

    private ?Carbon $date = null;

    /**
     * @param array $date
     *
     * @return Date
     */
    public static function fromDate(array $date): Date
    {
        return new self($date);
    }

    public static function fromString(string $date): Date
    {
        $dateArray = Carbon::parse($date)->toArray();

        return new self($dateArray);
    }

    /**
     * @inheritdoc
     */
    protected function guard($date): void
    {
        $this->date = $this->createDateFromData($date);
    }

    /** @phpcsSuppress Generic.NamingConventions.ConstructorName.OldStyle */
    public function date(): ?Carbon
    {
        return $this->date;
    }

    /**
     * @inheritDoc
     */
    public function equals(object $other): bool
    {
        if ($other instanceof self === false) {
            return false;
        }

        return $this->date()->eq($other->date());
    }
}
