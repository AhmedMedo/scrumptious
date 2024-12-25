<?php

namespace App\Libraries\Messaging\Event;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Messaging\Aggregate\EventBridge\AggregateChanged;
use ArrayIterator;
use ReflectionException;

class EventStorage
{
    private EventPublisher $eventPublisher;

    private EventStreamRepository $streamRepository;

    private ?AggregateChanged $tmpLastReleasedEvent = null;

    public function __construct(EventPublisher $eventPublisher, EventStreamRepository $streamRepository)
    {
        $this->eventPublisher = $eventPublisher;
        $this->streamRepository = $streamRepository;
    }

    /**
     * @param AggregateChanged $event
     *
     * @throws ReflectionException
     */
    public function release(AggregateChanged $event): self
    {
        $this->eventPublisher->release($event);

        $this->tmpLastReleasedEvent = $event;

        return $this;
    }

    public function record(): void
    {
        if ($this->tmpLastReleasedEvent !== null) {
            $this->streamRepository->save($this->tmpLastReleasedEvent);
        }
    }

    public function load(AggregateId $aggregateId, int $lastVersion): ArrayIterator
    {
        $iterator = new ArrayIterator();

        foreach ($this->streamRepository->load($aggregateId, $lastVersion) as $event) {
            $iterator->append($event);
        }

        return $iterator;
    }
}
