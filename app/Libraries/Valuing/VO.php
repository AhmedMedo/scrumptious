<?php

namespace App\Libraries\Valuing;

use InvalidArgumentException;

abstract class VO implements Stringify
{
    /** @var mixed */
    protected $value;

    /**
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    protected function __construct($value)
    {
        $this->guard($value);
        $this->setValue($value);
    }

    /**
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    abstract protected function guard($value): void;

    /**
     * @param mixed $value
     */
    protected function setValue($value): void
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @inheritdoc
     */
    public function toString(): string
    {
        return (string) $this->value;
    }

    public function equals(object $other): bool
    {
        if ($other instanceof self === false) {
            return false;
        }

        return $this->value === $other->value;
    }

    /**
     * @return mixed
     */
    public function raw()
    {
        return $this->value;
    }
}
