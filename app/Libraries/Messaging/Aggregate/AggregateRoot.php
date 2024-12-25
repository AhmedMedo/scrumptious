<?php

namespace App\Libraries\Messaging\Aggregate;

use App\Libraries\Messaging\Aggregate\EventBridge\AggregateChanged;
use App\Libraries\Messaging\Snapshot\Snapshot;
use ArrayIterator;

abstract class AggregateRoot
{
    protected int $version = 0;

    protected ?AggregateId $aggregateId = null;

    /** @var AggregateChanged[] */
    protected array $recordedEvents = [];

    protected function setAggregateId(AggregateId $aggregateId): void
    {
        $this->aggregateId = $aggregateId;
    }

    private function setLastVersion(int $version): AggregateRoot
    {
        $this->version = $version;

        return $this;
    }

    public function aggregateId(): string
    {
        return $this->aggregateId->toString();
    }

    public function version(): int
    {
        return $this->version;
    }

    public function aggregateType(): string
    {
        return get_called_class();
    }

    /**
     * @return AggregateChanged[]
     */
    public function popRecordedEvents(): array
    {
        $pendingEvents = $this->recordedEvents;

        $this->recordedEvents = [];

        return $pendingEvents;
    }

    protected function recordThat(AggregateChanged $event): void
    {
        ++$this->version;

        $this->recordedEvents[] = $event->withVersion($this->version);

        $this->apply($event);
    }

    /**
     * @param ArrayIterator $historyEvents
     *
     * @return AggregateRoot
     */
    public static function reconstituteFromHistory(ArrayIterator $historyEvents): self
    {
        // @phpstan-ignore-next-line
        $instance = new static();
        $instance->replay($historyEvents);

        return $instance;
    }

    public function reconstituteFromSnapshot(Snapshot $snapshot): void
    {
        $this->setLastVersion($snapshot->lastVersion());
    }

    public function replay(ArrayIterator $historyEvents): void
    {
        /** @var AggregateChanged $pastEvent */
        foreach ($historyEvents->getArrayCopy() as $pastEvent) {
            $this->setLastVersion($pastEvent->version())->apply($pastEvent);
        }
    }

    protected function apply(AggregateChanged $event): void
    {
        $event->populate($this);
    }
}
