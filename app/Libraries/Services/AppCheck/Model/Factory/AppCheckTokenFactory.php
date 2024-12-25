<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck\Model\Factory;

use App\Libraries\Services\AppCheck\Model\AppCheckToken;

class AppCheckTokenFactory
{
    public function fromDecoded(array $decoded): ?AppCheckToken
    {
        if ($decoded === []) {
            return null;
        }

        return new AppCheckToken(
            $decoded['aud'] ?? [],
            $decoded['iss'] ?? ''
        );
    }
}
