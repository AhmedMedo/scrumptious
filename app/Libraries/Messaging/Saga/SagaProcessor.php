<?php

namespace App\Libraries\Messaging\Saga;

use Exception;

final class SagaProcessor
{
    private SagaManager $sagaManager;

    public function __construct(SagaManager $sagaManager)
    {
        $this->sagaManager = $sagaManager;
    }

    /**
     * @param Scenario $scenario
     *
     * @throws Exception
     */
    public function run(Scenario $scenario): void
    {
        $this->sagaManager->handle($scenario);
    }
}
