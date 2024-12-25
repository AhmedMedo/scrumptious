<?php

namespace App\Libraries\Support\GoogleApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;

class GoogleApiClient
{
    private const API_URL = 'https://maps.googleapis.com/maps/api/';

    public function __construct(private readonly Client $guzzle, private readonly string $apiKey)
    {
    }

    /**
     * @throws GuzzleException
     */
    public function get(string $uri, array $query = []): ResponseInterface
    {
        return $this->guzzle->send($this->request($uri, $query));
    }

    private function request(string $uri, array $query = []): Request
    {
        $query['key'] = $this->apiKey;

        return new Request(
            'GET',
            $this->uri($uri)
                ->withQuery(http_build_query($query))
        );
    }

    private function uri(string $uri): Uri
    {
        $url = self::API_URL;
        $uri = trim($uri, '/');

        return new Uri("{$url}{$uri}");
    }
}
