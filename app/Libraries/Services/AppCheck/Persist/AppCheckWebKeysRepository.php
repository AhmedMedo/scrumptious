<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck\Persist;

use App\Libraries\Services\AppCheck\Http\AppCheckClient;
use Symfony\Component\HttpFoundation\Response;

class AppCheckWebKeysRepository
{
    public function __construct(
        private readonly AppCheckClient $client,
        private readonly AppCheckWebKeysCache $cache
    ) {
    }

    public function getWebKeys(): array
    {
        if ($this->cache->exists()) {
            return $this->cache->get();
        }

        return $this->client->getWebKeys()->body();
    }

    public function refreshWebKeys(): bool
    {
        $response = $this->client->getWebKeys();
        $cached = $response->code() === Response::HTTP_OK;

        if ($cached) {
            $this->cache->store($response->body());
        }

        return $cached;
    }
}
