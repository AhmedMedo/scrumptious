<?php

namespace App\Libraries\Messaging\Aggregate;

use App\Libraries\Messaging\Aggregate\EventBridge\AggregateChanged;
use ArrayIterator;

class AggregateTranslator
{
    protected AggregateRootDecorator $aggregateRootDecorator;

    public function __construct(AggregateRootDecorator $aggregateRootDecorator)
    {
        $this->aggregateRootDecorator = $aggregateRootDecorator;
    }

    public function reconstituteAggregateFromType(string $aggregateType, AggregateId $aggregateId): AggregateRoot
    {
        return $this->aggregateRootDecorator->fromAggregateData($aggregateType, $aggregateId);
    }

    public function reconstituteAggregateFromHistory(string $aggregateType, ArrayIterator $historyEvents): AggregateRoot
    {
        return $this->aggregateRootDecorator->fromHistory($aggregateType, $historyEvents);
    }

    /**
     * @param AggregateRoot $aggregateRoot
     *
     * @return AggregateChanged[]
     */
    public function extractPendingStreamEvents(AggregateRoot $aggregateRoot): array
    {
        return $this->aggregateRootDecorator->extractRecordedEvents($aggregateRoot);
    }
}
