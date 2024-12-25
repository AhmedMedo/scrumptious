<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck\Decoder;

use Firebase\JWT\JWK;

class AppCheckWebKeysDecoder
{
    public function encode(array $keys): array
    {
        return JWK::parseKeySet($keys);
    }
}
