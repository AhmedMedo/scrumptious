<?php

namespace App\Libraries\Valuing\Identity;

use App\Libraries\Valuing\VO;
use InvalidArgumentException;
use Webmozart\Assert\Assert as Assertion;

/**
 * @property Uuid\Collection $value
 */
final class Uuids extends VO
{
    /**
     * @param array $data
     *
     * @return Uuids
     * @throws InvalidArgumentException
     *
     */
    public static function fromArray(array $data): Uuids
    {
        return new self($data);
    }

    /**
     * @param Uuids|object $other
     *
     * @return bool
     */
    public function equals(object $other): bool
    {
        if ($other instanceof self === false) {
            return false;
        }

        return $this->value->equals($other->value);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_keys($this->value->getArrayCopy());
    }

    /**
     * @inheritdoc
     */
    protected function guard($value): void
    {
        Assertion::isArray($value, 'Invalid Uuids array');
    }

    /**
     * @inheritdoc
     *
     * @throws InvalidArgumentException
     */
    protected function setValue($data): void
    {
        $this->value = new Uuid\Collection();

        foreach ($data as $uuid) {
            $this->addUuid($uuid);
        }
    }

    /**
     * @param string $uuid
     *
     * @throws InvalidArgumentException
     */
    public function addUuid(string $uuid): void
    {
        $this->value->add(Uuid::fromIdentity($uuid));
    }
}
