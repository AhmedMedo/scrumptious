<?php

namespace App\Libraries\Base\Lock\Database;

use App\Libraries\Base\Lock\MutexService;

class NullMutex implements MutexService
{
    public function forName(string $name): MutexService
    {
        return $this;
    }

    public function releaseBadLock(): void
    {
    }

    public function releaseLock(): bool
    {
        return true;
    }

    public function acquireLock(bool $shouldBlock = true, int $timeout = 60): bool
    {
        return true;
    }
}
