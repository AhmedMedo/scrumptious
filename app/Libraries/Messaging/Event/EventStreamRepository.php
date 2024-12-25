<?php

namespace App\Libraries\Messaging\Event;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Messaging\Aggregate\EventBridge\AggregateChanged;

interface EventStreamRepository
{
    public function save(AggregateChanged $event): void;

    /**
     * @param AggregateId $aggregateId
     * @param int $lastVersion
     *
     * @return AggregateChanged[]
     */
    public function load(AggregateId $aggregateId, int $lastVersion): array;
}
