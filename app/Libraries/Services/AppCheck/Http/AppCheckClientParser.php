<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck\Http;

use App\Libraries\Messaging\Infrastructure\Service\JsonSerializer;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class AppCheckClientParser
{
    private JsonSerializer $jsonSerializer;

    public function __construct(JsonSerializer $jsonSerializer)
    {
        $this->jsonSerializer = $jsonSerializer;
    }

    public function toJsonResponse(ResponseInterface $response): AppCheckClientResponse
    {
        try {
            $body = $this->jsonSerializer->decode((string) $response->getBody());
        } catch (RuntimeException) {
            $body = [];
        }

        return new AppCheckClientResponse($response->getStatusCode(), $body);
    }
}
