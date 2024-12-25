<?php

namespace App\Libraries\Messaging\Saga;

use ArrayIterator;

final class State
{
    private string $id;

    private ?string $aggregateId;

    private bool $done;

    private ArrayIterator $payload;

    /**
     *
     * @param string $id
     * @param string|null $aggregateId
     * @param array $payload
     */
    public function __construct(string $id, string $aggregateId = null, array $payload = [])
    {
        $this->id = $id;
        $this->done = false;
        $this->withAggregateId($aggregateId);
        $this->payload = new ArrayIterator($payload);
    }

    /**
     * @param string|null $aggregateId
     */
    public function withAggregateId(string $aggregateId = null): void
    {
        $this->aggregateId = $aggregateId;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): self
    {
        $this->payload->offsetSet($key, $value);

        return $this;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if ($this->payload->offsetExists($key) === false) {
            return $default;
        }

        return $this->payload->offsetGet($key);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function isDone(): bool
    {
        return $this->done;
    }

    public function aggregateId(): ?string
    {
        return $this->aggregateId;
    }

    /**
     * @return array
     */
    public function payload(): array
    {
        return $this->payload->getArrayCopy();
    }

    public function markDone(): self
    {
        $state = clone $this;
        $state->done = true;

        return $state;
    }
}
