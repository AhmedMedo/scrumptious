<?php

namespace App\Components\Content\Infrastructure\Http\Handler;

use App\Components\Content\Application\Mapper\CountryViewMapper;
use App\Components\Content\Application\Repository\CountryRepositoryInterface;
use App\Components\Content\Data\Entity\Brand;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/content/brands',
    description: 'Get Brands',
    summary: 'Get Brands',
    tags: ['Content'],
    responses  : [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(properties: [
                    new OA\Property(property: 'uuid', type: 'string'),
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'image', type: 'string'),
                ])),
            ],
            type      : 'object'
        )),
    ]
)]

class BrandsHandler extends Handler
{
    public function __invoke(): JsonResponse
    {
        return $this->successResponseWithData(
            Brand::query()->where('is_active', true)->get()->map(fn($brand) => [
                'uuid' => $brand->uuid,
                'name' => $brand->name,
                'image' => $brand->getFirstMediaUrl('image'),
            ])->toArray()
        );
    }
}
