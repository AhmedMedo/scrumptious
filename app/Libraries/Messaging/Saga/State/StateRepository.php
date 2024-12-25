<?php

namespace App\Libraries\Messaging\Saga\State;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Messaging\Saga\State;

interface StateRepository
{
    public function findOneBy(AggregateId $aggregateId, string $sagaType): ?State;

    public function save(State $state, string $sagaType): void;
}
