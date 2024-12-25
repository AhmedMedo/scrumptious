<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck;

use App\Libraries\Services\AppCheck\Verifier\AppCheckTokenVerifier;

class AppCheckService
{
    public function __construct(
        private readonly AppCheckTokenVerifier $tokenVerifier,
        private readonly AppCheckConfiguration $configuration
    ) {
    }

    public function verifyToken(string $signature): void
    {
        if ($this->configuration->isEnabled()) {
            $this->tokenVerifier->verify($signature);
        }
    }
}
