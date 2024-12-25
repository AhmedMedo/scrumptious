<?php

namespace App\Libraries\Messaging\Infrastructure;

use App\Libraries\Base\Database\ConnectionService;
use App\Libraries\Base\Database\MySQL;
use App\Libraries\Base\Lock\Database as Mutex;
use App\Libraries\Base\Lock\MutexService;
use App\Libraries\Base\Support\ServiceProvider;
use App\Libraries\Messaging\Event\EventStreamRepository;
use App\Libraries\Messaging\Infrastructure\Persist\EventStreamEloquentRepository;
use App\Libraries\Messaging\Infrastructure\Persist\SnapshotEloquentRepository;
use App\Libraries\Messaging\Infrastructure\Persist\StateEloquentRepository;
use App\Libraries\Messaging\Saga\Metadata\MetadataFactory;
use App\Libraries\Messaging\Saga\Metadata\SagaMetadataFactory;
use App\Libraries\Messaging\Saga\State\StateRepository;
use App\Libraries\Messaging\Snapshot\SnapshotRepository;
use App\Libraries\Valuing\Identity\IdentityGenerator;
use App\Libraries\Valuing\Identity\Uuid\UuidGenerator;

class MessageServiceProvider extends ServiceProvider
{
    protected function testingBindings(): array
    {
        return [
            ConnectionService::class => MySQL\NullConnectionService::class,
            MutexService::class => Mutex\NullMutex::class,
        ];
    }

    /**
     * @inheritDoc
     */
    protected function regularBindings(): array
    {
        return [
            MetadataFactory::class => SagaMetadataFactory::class,

            IdentityGenerator::class     => UuidGenerator::class,
            EventStreamRepository::class => EventStreamEloquentRepository::class,
            SnapshotRepository::class    => SnapshotEloquentRepository::class,
            StateRepository::class       => StateEloquentRepository::class,

            MutexService::class      => Mutex\MysqlMutex::class,
            ConnectionService::class => MySQL\ConnectionService::class,
        ];
    }
}
