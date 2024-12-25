<?php

namespace App\Libraries\Messaging\Snapshot;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Messaging\Aggregate\AggregateRoot;

class SnapshotStorage
{
    private SnapshotRepository $repository;

    public function __construct(SnapshotRepository $repository)
    {
        $this->repository = $repository;
    }

    public function make(AggregateRoot $aggregateRoot): void
    {
        $this->repository->save(new Snapshot($aggregateRoot, $aggregateRoot->version()));
    }

    public function get(string $aggregateType, AggregateId $aggregateId): Snapshot
    {
        return $this->repository->get($aggregateType, $aggregateId);
    }
}
