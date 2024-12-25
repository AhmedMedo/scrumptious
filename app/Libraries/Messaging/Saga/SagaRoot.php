<?php

namespace App\Libraries\Messaging\Saga;

use App\Libraries\Messaging\Saga\Metadata\Metadata;
use BadMethodCallException;

abstract class SagaRoot
{
    /**
     * @return array
     */
    abstract public function configuration(): array;

    public function handle(Metadata $metadata, Scenario $scenario, State $state): State
    {
        $method = $this->getHandleMethod($scenarioName = $metadata->getClassName($scenario));

        if (! method_exists($this, $method)) {
            $message = sprintf("No handle method '%s' for event '%s'.", $method, $scenarioName);

            throw new BadMethodCallException($message);
        }

        return $this->$method($scenario, $state);
    }

    private function getHandleMethod(string $scenarioName): string
    {
        $classParts = explode('\\', $scenarioName);

        return 'run' . end($classParts);
    }
}
