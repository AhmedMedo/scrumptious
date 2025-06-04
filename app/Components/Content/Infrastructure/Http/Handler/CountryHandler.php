<?php

namespace App\Components\Content\Infrastructure\Http\Handler;

use App\Components\Content\Application\Mapper\CountryViewMapper;
use App\Components\Content\Application\Repository\CountryRepositoryInterface;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/content/countries',
    description: 'Get countries',
    summary: 'Get countries',
    tags: ['Content'],
    responses  : [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(properties: [
                    new OA\Property(property: 'uuid', type: 'string'),
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'flag', type: 'string'),
                    new OA\Property(property: 'iso_code', type: 'string'),
                    new OA\Property(property: 'iso3_code', type: 'string'),
                    new OA\Property(property: 'country_code', type: 'string'),
                    new OA\Property(property: 'currency_code', type: 'string'),
                    new OA\Property(property: 'created_at', type: 'string'),
                ])),
            ],
            type      : 'object'
        )),
    ]
)]

class CountryHandler extends Handler
{
    public function __construct(private readonly CountryViewMapper $countryViewMapper, private readonly CountryRepositoryInterface $countryRepository)
    {
    }

    public function __invoke(): JsonResponse
    {
        return $this->successResponseWithData(
            $this->countryViewMapper->toArray(
                $this->countryRepository->findAll()
            )
        );
    }
}
