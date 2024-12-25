<?php

namespace App\Libraries\Valuing\Identity;

use App\Libraries\Valuing\VO;
use Webmozart\Assert\Assert as Assertion;

/**
 * @property Id\Collection $value
 */
final class Ids extends VO
{
    /**
     * @param array $data
     *
     * @return Ids
     */
    public static function fromArray(array $data): Ids
    {
        return new self($data);
    }

    /**
     * @param Ids|object $other
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
        Assertion::isArray($value, 'Invalid Ids array');
    }

    /**
     * @inheritdoc
     */
    protected function setValue($data): void
    {
        $this->value = new Id\Collection();

        foreach ($data as $id) {
            $this->addId((int) $id);
        }
    }

    public function addId(int $id): void
    {
        $this->value->add(Id::fromIdentity($id));
    }
}
