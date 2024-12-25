<?php

namespace App\Libraries\Messaging\Infrastructure\Persist;

use App\Component\Account\DomainModel\Auth\AuthService;
use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Messaging\Aggregate\EventBridge\AggregateChanged;
use App\Libraries\Messaging\Event\EventStreamRepository;
use App\Libraries\Messaging\Infrastructure\Entity\EventStreamEntity;
use App\Libraries\Messaging\Infrastructure\Service\JsonSerializer;
use Jenssegers\Agent\Agent;

class EventStreamEloquentRepository implements EventStreamRepository
{
    private Agent $agent;

    private JsonSerializer $serializer;

    private AuthService $authService;

    public function __construct(Agent $agent, JsonSerializer $serializer, AuthService $authService)
    {
        $this->agent = $agent;
        $this->serializer = $serializer;
        $this->authService = $authService;
    }

    /**
     * @inheritDoc
     */
    public function save(AggregateChanged $event): void
    {
        $where = ['event_id' => $event->aggregateId(), 'version' => $event->version()];

        EventStreamEntity::query()->updateOrCreate($where, $this->extractCreateData($event));
    }

    /**
     * @param AggregateChanged $event
     *
     * @return array{event_id: string, event_name: string, version: int, payload: string, metadata: string, user_uuid: string|null}
     */
    private function extractCreateData(AggregateChanged $event): array
    {
        return array_merge($event->baseData(), [
            'payload'   => $this->serializer->encode($event->payload()),
            'metadata'  => $this->serializer->encode($this->extractMetadata()),
            'user_uuid' => $this->authService->check() ? $this->authService->user()->uuid() : null,
        ]);
    }

    /**
     * @return array{device: bool|string, platform: bool|string, platform_version: float|string, browser: bool|string, browser_version: float|string, client_ip: string|null, client_host: string|null, environment: string}
     */
    private function extractMetadata(): array
    {
        $metadata = [];

        $metadata['device'] = $this->agent->device();
        $metadata['platform'] = $this->agent->platform();
        $metadata['platform_version'] = $this->agent->version($metadata['platform']);
        $metadata['browser'] = $this->agent->browser();
        $metadata['browser_version'] = $this->agent->version($metadata['browser']);
        $metadata['client_ip'] = $this->clientIP();
        $metadata['client_host'] = $this->clientHost();
        $metadata['environment'] = PHP_SAPI;

        return $metadata;
    }

    private function clientIP(): ?string
    {
        return optional(request())->ip();
    }

    private function clientHost(): string
    {
        return request()->getHost();
    }

    /**
     * @inheritDoc
     */
    public function load(AggregateId $aggregateId, int $lastVersion): array
    {
        $collection = EventStreamEntity::query()
            ->selectRaw('event_storage.*, user.full_name AS blamable')
            ->leftJoin('user', 'user.uuid', '=', 'event_storage.user_uuid')
            ->where(['event_id' => $aggregateId->toString()])
            ->where('version', '>=', $lastVersion)
            ->get();

        if ($collection->count() === 0) {
            return [];
        }

        $stream = [];

        /** @var EventStreamEntity $entity */
        foreach ($collection as $entity) {
            /** @var AggregateChanged $event */
            $event = $entity->event_name;
            $event = $event::occur($entity->event_id, $this->serializer->decode($entity->payload));

            $stream[] = $event->withVersion($entity->version)
                ->withBlamable((string) $entity->blamable)
                ->withRecordedAt($entity->created_at);
        }

        return $stream;
    }
}
