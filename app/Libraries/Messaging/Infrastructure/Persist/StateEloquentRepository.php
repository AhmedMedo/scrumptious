<?php

namespace App\Libraries\Messaging\Infrastructure\Persist;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Messaging\Infrastructure\Entity\StateEntity;
use App\Libraries\Messaging\Saga\State;
use App\Libraries\Messaging\Saga\State\StateRepository;
use Carbon\Carbon;

final class StateEloquentRepository implements StateRepository
{
    private StateEntity $db;

    public function __construct(StateEntity $db)
    {
        $this->db = $db;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBy(AggregateId $aggregateId, string $sagaType): ?State
    {
        $query = $this->db->newQuery()
            ->where('aggregate_id', '=', $aggregateId->toString())
            ->where('saga_type', '=', $sagaType)
            ->where('is_done', '=', false);

        if ($entity = $query->first()) {
            assert($entity instanceof StateEntity);

            return new State($entity->uuid, $entity->aggregate_id, $entity->payload);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function save(State $state, string $sagaType): void
    {
        $conditions = ['aggregate_id' => $state->aggregateId(), 'saga_type' => $sagaType, 'is_done' => false];
        if ($entity = $this->db->newQuery()->where($conditions)->first()) {
            $entity->update(['payload' => $state->payload(), 'is_done' => $state->isDone()]);
        } else {
            $this->db->newQuery()->create(array_merge($conditions, [
                'payload'     => $state->payload(),
                'is_done'     => $state->isDone(),
                'recorded_on' => Carbon::now()->toDateTimeString(),
            ]));
        }
    }
}
