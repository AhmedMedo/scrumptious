<?php

namespace App\Libraries\Messaging\Saga\State;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Messaging\Saga\State;
use Illuminate\Support\Str;

final class StateManager
{
    private StateRepository $repository;

    public function __construct(StateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findOneByAggregateSaga(?AggregateId $aggregateId, string $sagaType): ?State
    {
        if ($aggregateId instanceof AggregateId) {
            return $this->repository->findOneBy($aggregateId, $sagaType);
        }

        return new State(Str::uuid()->toString());
    }

    public function store(State $state, string $sagaType): void
    {
        $this->repository->save($state, $sagaType);
    }
}
