<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck\Decoder;

use App\Libraries\Services\AppCheck\Exception\AppCheckTokenException;
use App\Libraries\Services\AppCheck\Persist\AppCheckWebKeysRepository;
use DomainException;
use Firebase\JWT\JWT;
use InvalidArgumentException;
use UnexpectedValueException;

class AppCheckTokenDecoder
{
    public function __construct(
        private readonly AppCheckWebKeysRepository $webKeys,
        private readonly AppCheckWebKeysDecoder $decoder
    ) {
    }

    public function decode(string $token): array
    {
        $webKeys = $this->webKeys->getWebKeys();

        if ($webKeys === []) {
            return [];
        }

        $keys = $this->decoder->encode($webKeys);

        try {
            return (array) JWT::decode($token, $keys);
        } catch (
            InvalidArgumentException |
            DomainException |
            UnexpectedValueException $exception
        ) {
            throw AppCheckTokenException::invalid($exception->getMessage());
        }
    }
}
