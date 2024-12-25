<?php

declare(strict_types=1);

namespace App\Libraries\Services\AppCheck\Http;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException;

class AppCheckClient
{
    public function __construct(
        private readonly HttpClient $httpClient,
        private readonly AppCheckClientRouter $router,
        private readonly AppCheckClientParser $parser
    ) {
    }

    public function getWebKeys(): AppCheckClientResponse
    {
        try {
            $response = $this->httpClient->get($this->router->getWebKeysUrl());
        } catch (BadResponseException $exception) {
            $response = $exception->getResponse();
        }

        return $this->parser->toJsonResponse($response);
    }
}
