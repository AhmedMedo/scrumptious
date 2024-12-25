<?php

namespace App\Libraries\Messaging\Snapshot;

use App\Libraries\Messaging\Aggregate\AggregateId;

interface SnapshotRepository
{
    public function save(Snapshot $snapshot): void;

    public function get(string $aggregateType, AggregateId $aggregateId): Snapshot;
}
