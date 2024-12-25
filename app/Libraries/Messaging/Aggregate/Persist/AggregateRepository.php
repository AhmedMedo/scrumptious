<?php

namespace App\Libraries\Messaging\Aggregate\Persist;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Messaging\Aggregate\AggregateRoot;
use App\Libraries\Messaging\Event\EventStorage;
use App\Libraries\Messaging\Snapshot\SnapshotStorage;
use Exception;

abstract class AggregateRepository
{
    protected EventStorage $eventStorage;

    protected SnapshotStorage $snapshotStorage;

    public function __construct(EventStorage $eventStorage, SnapshotStorage $snapshotStorage)
    {
        $this->eventStorage = $eventStorage;
        $this->snapshotStorage = $snapshotStorage;
    }

    abstract public function getAggregateRootClass(): string;

    /**
     * @param AggregateRoot $aggregateRoot
     *
     * @throws Exception
     */
    protected function saveAggregateRoot(AggregateRoot $aggregateRoot): void
    {
        foreach ($aggregateRoot->popRecordedEvents() as $aggregateChanged) {
            $this->eventStorage->release($aggregateChanged)->record();
        }

        $this->snapshotStorage->make($aggregateRoot);
    }

    protected function findAggregateRoot(AggregateId $aggregateId): AggregateRoot
    {
        $snapshot = $this->snapshotStorage->get($this->getAggregateRootClass(), $aggregateId);
        $events = $this->eventStorage->load($aggregateId, $snapshot->lastVersion() + 1);

        $aggregateRoot = $snapshot->aggregateRoot();
        $aggregateRoot->reconstituteFromSnapshot($snapshot);
        $aggregateRoot->replay($events);

        return $aggregateRoot;
    }
}
