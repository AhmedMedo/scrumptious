<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck\Http;

use App\Libraries\Services\AppCheck\AppCheckConfiguration;

class AppCheckClientRouter
{
    public function __construct(private readonly AppCheckConfiguration $configuration)
    {
    }

    public function getWebKeysUrl(): string
    {
        return $this->apiUrl('/v1/jwks');
    }

    public function getProjectUrl(): string
    {
        return $this->apiUrl($this->configuration->projectNumber());
    }

    private function apiUrl(string $path = ''): string
    {
        return rtrim(sprintf('https://firebaseappcheck.googleapis.com/%s', ltrim($path, '/')), '/');
    }
}
