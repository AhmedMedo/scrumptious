<?php

namespace App\Libraries\Query;

use ArrayIterator;
use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Webmozart\Assert\Assert;

abstract class Collection extends ArrayIterator implements Arrayable
{
    public function __construct(array $items = [])
    {
        // validate all items on construct
        array_map(Closure::fromCallable([$this, 'assertItemCanBePartOfCollection']), $items);
        parent::__construct($items);
    }

    abstract protected function itemsClass(): string;

    /** @param mixed $value */
    public function append($value): void
    {
        $this->assertItemCanBePartOfCollection($value);
        parent::append($value);
    }

    /**
     * @param string|int $key
     * @param mixed $value
     */
    public function offsetSet($key, $value): void
    {
        $this->assertItemCanBePartOfCollection($key);
        parent::offsetSet($key, $value);
    }

    /** @param mixed $item */
    protected function assertItemCanBePartOfCollection($item): void
    {
        Assert::isInstanceOf(
            $item,
            $this->itemsClass(),
            sprintf('[%s] can contains only [%s] items.', static::class, $this->itemsClass())
        );
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return $this->getArrayCopy();
    }
}
