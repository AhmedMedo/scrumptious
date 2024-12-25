<?php

namespace App\Libraries\Base\Lock;

interface MutexService
{
    public function forName(string $name): MutexService;

    public function releaseBadLock(): void;

    public function releaseLock(): bool;

    /**
     * @throws Exception\MutexFailedException
     */
    public function acquireLock(bool $shouldBlock = true, int $timeout = 60): bool;
}
