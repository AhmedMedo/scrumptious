<?php

namespace App\Libraries\Messaging\Infrastructure\Persist;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Messaging\Aggregate\AggregateTranslator;
use App\Libraries\Messaging\Infrastructure\Entity\SnapshotEntity;
use App\Libraries\Messaging\Snapshot\Snapshot;
use App\Libraries\Messaging\Snapshot\SnapshotRepository;

class SnapshotEloquentRepository implements SnapshotRepository
{
    private SnapshotEntity $db;

    private AggregateTranslator $translator;

    public function __construct(SnapshotEntity $db, AggregateTranslator $translator)
    {
        $this->db = $db;
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function save(Snapshot $snapshot): void
    {
        $this->db->newQuery()->updateOrCreate($this->extractCreateData($snapshot), [
            'last_version' => $snapshot->lastVersion(),
        ]);
    }

    /**
     * @param Snapshot $snapshot
     *
     * @return array{aggregate_uuid: string, aggregate_type: string}
     */
    private function extractCreateData(Snapshot $snapshot): array
    {
        return [
            'aggregate_uuid' => $snapshot->aggregateRoot()->aggregateId(),
            'aggregate_type' => $snapshot->aggregateType(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function get(string $aggregateType, AggregateId $aggregateId): Snapshot
    {
        $aggregateRoot = $this->translator->reconstituteAggregateFromType($aggregateType, $aggregateId);
        $condition = ['aggregate_uuid' => $aggregateId->toString(), 'aggregate_type' => $aggregateType];
        /** @var SnapshotEntity $entity */
        $entity = $this->db->newQuery()->where($condition)->first();

        if ($entity) {
            return new Snapshot($aggregateRoot, $entity->last_version);
        }

        return new Snapshot($aggregateRoot, 0);
    }
}
