<?php

namespace App\Libraries\Messaging\Snapshot;

use App\Libraries\Messaging\Aggregate\AggregateRoot;

class Snapshot
{
    private AggregateRoot $aggregateRoot;

    private int $lastVersion;

    public function __construct(AggregateRoot $aggregateRoot, int $lastVersion)
    {
        $this->aggregateRoot = $aggregateRoot;
        $this->lastVersion = $lastVersion;
    }

    public function aggregateRoot(): AggregateRoot
    {
        return $this->aggregateRoot;
    }

    public function aggregateType(): string
    {
        return $this->aggregateRoot->aggregateType();
    }

    public function lastVersion(): int
    {
        return $this->lastVersion;
    }
}
