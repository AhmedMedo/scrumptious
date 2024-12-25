<?php

namespace App\Libraries\Messaging\Saga;

use App\Libraries\Base\Database\ConnectionService;
use App\Libraries\Messaging\Saga\Metadata\MetadataFactory;
use App\Libraries\Messaging\Saga\State\StateManager;
use Exception;

final class SagaManager
{
    private ConnectionService $connection;

    private SagaRegistry $sagaRegistry;

    private StateManager $stateManager;

    private MetadataFactory $metadataFactory;

    public function __construct(
        ConnectionService $connection,
        SagaRegistry $sagaRegistry,
        StateManager $stateManager,
        MetadataFactory $metadataFactory
    ) {
        $this->connection = $connection;
        $this->sagaRegistry = $sagaRegistry;
        $this->stateManager = $stateManager;
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * @param Scenario $scenario
     *
     * @throws Exception
     */
    public function handle(Scenario $scenario): void
    {
        foreach ($this->sagaRegistry->all() as $sagaType => $saga) {
            $metadata = $this->metadataFactory->create($saga);

            if ($metadata->handles($scenario) === false) {
                continue;
            }

            $state = $this->stateManager->findOneByAggregateSaga($metadata->criteria($scenario), $sagaType);
            if ($state === null) {
                continue;
            }

            try {
                $this->connection->beginTransaction();
                $this->stateManager->store($saga->handle($metadata, $scenario, $state), $sagaType);
                $this->connection->commit();
            } catch (Exception $exception) {
                $this->connection->rollBack();

                throw $exception;
            }
        }
    }
}
