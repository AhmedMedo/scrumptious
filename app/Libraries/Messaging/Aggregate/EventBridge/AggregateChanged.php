<?php

namespace App\Libraries\Messaging\Aggregate\EventBridge;

use App\Libraries\Messaging\Aggregate\AggregateRoot;
use Carbon\Carbon;

abstract class AggregateChanged
{
    protected int $version = 0;

    protected string $blamable = '';

    protected Carbon $recordedAt;

    protected string $aggregateId;

    protected array $payload = [];

    /**
     *
     * @param string $aggregateId
     * @param array $payload
     * @param int $version
     * @param string $blamable
     */
    protected function __construct(string $aggregateId, array $payload, int $version = 1, string $blamable = '')
    {
        $this->setAggregateId($aggregateId);
        $this->setVersion($version);
        $this->setBlamable($blamable);
        $this->setRecordedAt(now());
        $this->setPayload($payload);
    }

    /**
     * @param string $aggregateId
     * @param array $payload
     *
     * @return AggregateChanged
     */
    public static function occur(string $aggregateId, array $payload = []): self
    {
        // @phpstan-ignore-next-line
        return new static($aggregateId, $payload);
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    /**
     * @return array
     */
    public function payload(): array
    {
        return $this->payload;
    }

    public function version(): int
    {
        return $this->version;
    }

    public function blamable(): string
    {
        return $this->blamable;
    }

    public function recordedAt(): Carbon
    {
        return $this->recordedAt;
    }

    public function eventName(): string
    {
        return get_called_class();
    }

    /**
     * @return array{event_id: string, event_name: string, version: int}
     */
    public function baseData(): array
    {
        return [
            'event_id'   => $this->aggregateId(),
            'event_name' => $this->eventName(),
            'version'    => $this->version(),
        ];
    }

    abstract public function populate(AggregateRoot $aggregateRoot): void;

    protected function setVersion(int $version): void
    {
        $this->version = $version;
    }

    protected function setBlamable(string $blamable): void
    {
        $this->blamable = $blamable;
    }

    protected function setRecordedAt(Carbon $recordedAt): void
    {
        $this->recordedAt = $recordedAt;
    }

    /**
     * @param int $version
     *
     * @return AggregateChanged
     */
    public function withVersion(int $version): self
    {
        $self = clone $this;
        $self->setVersion($version);

        return $self;
    }

    /**
     * @param string $blamable
     *
     * @return AggregateChanged
     */
    public function withBlamable(string $blamable): self
    {
        $self = clone $this;
        $self->setBlamable($blamable);

        return $self;
    }

    /**
     * @param Carbon $recordedAt
     *
     * @return $this
     */
    public function withRecordedAt(Carbon $recordedAt): self
    {
        $self = clone $this;
        $self->setRecordedAt($recordedAt);

        return $self;
    }

    protected function setAggregateId(string $aggregateId): void
    {
        $this->aggregateId = $aggregateId;
    }

    /**
     * @param array $payload
     */
    protected function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }
}
