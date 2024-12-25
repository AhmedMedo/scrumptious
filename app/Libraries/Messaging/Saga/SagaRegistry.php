<?php

namespace App\Libraries\Messaging\Saga;

final class SagaRegistry
{
    /** @var SagaRoot[] */
    private array $saga;

    public function __construct()
    {
        $this->saga = [];
    }

    public function register(SagaRoot $saga): self
    {
        $this->saga[get_class($saga)] = $saga;

        return $this;
    }

    /**
     * @return SagaRoot[]
     */
    public function all(): array
    {
        return $this->saga;
    }
}
