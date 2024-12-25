<?php

namespace App\Libraries\Base\Lock\Database;

use App\Libraries\Base\Lock\Exception\MutexFailedException;
use App\Libraries\Base\Lock\MutexService;
use Exception;
use Illuminate\Support\Facades\DB;

class MysqlMutex implements MutexService
{
    private ?string $name = null;

    private bool $hasLock = false;

    /**
     * @inheritDoc
     */
    public function forName(string $name): MutexService
    {
        if (strlen($name) > 64) {
            throw new Exception('Mutex name is more than 64 characters.');
        }

        $this->name = $name;

        return $this;
    }

    public function releaseBadLock(): void
    {
        $this->hasLock = true;
        $this->releaseLock();
    }

    /**
     * @inheritDoc
     */
    public function acquireLock($shouldBlock = true, $timeout = 60): bool
    {
        if (false === $shouldBlock) {
            $timeout = 0;
        }

        if (true === $this->hasLock) {
            return true;
        }

        $result = DB::selectOne('SELECT IS_FREE_LOCK(:name) AS `result`', ['name' => $this->name]);

        if (1 === $result->result) {
            $result = DB::selectOne('SELECT GET_LOCK(:name, :timeout) AS `result`', [
                'name'    => $this->name,
                'timeout' => $timeout,
            ]);

            if (1 === $result->result) {
                $this->hasLock = true;

                return true;
            }

            $this->hasLock = false;

            throw new MutexFailedException($this->name);
        }

        $this->hasLock = false;

        throw new MutexFailedException($this->name);
    }

    /**
     * @inheritDoc
     */
    public function releaseLock(): bool
    {
        if (true === $this->hasLock) {
            DB::statement('SELECT RELEASE_LOCK(:name) AS `result`', ['name' => $this->name]);
            $this->hasLock = false;
        }

        return true;
    }
}
