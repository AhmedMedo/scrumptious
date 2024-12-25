<?php

namespace App\Libraries\Support\GoogleApi\GooglePlacesApi;

use App\Libraries\Support\GoogleApi\GoogleApiClient;
use GuzzleHttp\Exception\GuzzleException;

class GooglePlacesApiService
{
    public function __construct(private readonly GoogleApiClient $googleApiClient)
    {
    }

    /**
     * @throws GuzzleException
     */
    public function searchPlaces(array $options): array
    {
        $response = $this->googleApiClient->get('place/textsearch/json', $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function placeDetails(string $placeId, array $options): array
    {
        $options['place_id'] = $placeId;
        $response = $this->googleApiClient->get('place/details/json', $options);

        return json_decode($response->getBody()->getContents(), true);
    }
}
