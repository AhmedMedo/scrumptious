<?php

namespace App\Libraries\Valuing\Geo;

use App\Libraries\Valuing as VO;
use App\Libraries\Valuing\Number\Decimal;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

use function sprintf;

final class Point extends VO\VO
{
    private ?Decimal $lat = null;

    private ?Decimal $lng = null;

    /**
     * @param float $lat
     * @param float $lng
     *
     * @return Point
     * @throws InvalidArgumentException
     *
     */
    public static function fromCoordinates(float $lat, float $lng): Point
    {
        return new self([
            'lat' => $lat,
            'lng' => $lng,
        ]);
    }

    public static function empty(): Point
    {
        return self::fromCoordinates(0.0, 0.0);
    }

    public function isEmpty(): bool
    {
        return $this->latitude() === 0.0 && $this->longitude() === 0.0;
    }

    public function latitude(): float
    {
        return $this->lat->raw();
    }

    public function longitude(): float
    {
        return $this->lng->raw();
    }

    public function toString(): string
    {
        return sprintf(
            '%s %s',
            $this->lat->toString(),
            $this->lng->toString()
        );
    }

    /**
     * @inheritDoc
     */
    protected function guard($coordinates): void
    {
        Assertion::greaterThan($coordinates['lat'], -90.0, 'Expected latitude coordinate greater than %2$s. Got: %s');
        Assertion::lessThan($coordinates['lat'], 90.0, 'Expected latitude coordinate less than %2$s. Got: %s');
        Assertion::greaterThan($coordinates['lng'], -180.0, 'Expected longitude coordinate greater than %2$s. Got: %s');
        Assertion::lessThan($coordinates['lng'], 180.0, 'Expected longitude coordinate less than %2$s. Got: %s');
    }

    /**
     * @param array|mixed $coordinates
     */
    protected function setValue($coordinates): void
    {
        $this->lat = Decimal::fromFloat($coordinates['lat']);
        $this->lng = Decimal::fromFloat($coordinates['lng']);
        parent::setValue($coordinates);
    }
}
