<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck\Persist;

use App\Libraries\Services\AppCheck\AppCheckConfiguration;
use Illuminate\Cache\Repository;

class AppCheckWebKeysCache
{
    public function __construct(private readonly Repository $cache, private readonly AppCheckConfiguration $configuration)
    {
    }

    public function store(array $keys): void
    {
        $this->cache->set($this->key(), $keys, $this->configuration->cacheTtl());
    }

    public function get(): array
    {
        return (array) $this->cache->get($this->key(), []);
    }

    public function exists(): bool
    {
        return $this->cache->has($this->key());
    }

    private function key(): string
    {
        return 'firebase-app-check-public-keys';
    }
}
