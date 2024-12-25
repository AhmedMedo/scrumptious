<?php

namespace App\Libraries\Messaging\Saga\Metadata;

use App\Libraries\Messaging\Aggregate\AggregateId;
use App\Libraries\Messaging\Saga\SagaRoot;
use App\Libraries\Messaging\Saga\Scenario;

final class Metadata
{
    private SagaRoot $saga;

    public function __construct(SagaRoot $saga)
    {
        $this->saga = $saga;
    }

    public function handles(Scenario $scenario): bool
    {
        $scenarioName = $this->getClassName($scenario);

        return isset($this->saga->configuration()[$scenarioName]);
    }

    public function criteria(Scenario $scenario): ?AggregateId
    {
        $scenarioName = $this->getClassName($scenario);

        if (isset($this->saga->configuration()[$scenarioName]) === true) {
            return $this->saga->configuration()[$scenarioName]($scenario);
        }

        return null;
    }

    public function getClassName(Scenario $scenario): string
    {
        return get_class($scenario);
    }
}
