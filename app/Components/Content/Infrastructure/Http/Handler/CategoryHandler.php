<?php

namespace App\Components\Content\Infrastructure\Http\Handler;

use App\Components\Content\Application\Query\CategoryQueryInterface;
use App\Components\Content\Data\Entity\CategoryEntity;
use App\Components\Content\Infrastructure\Query\CategoryQuery;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/content/categories',
    description: 'Get countries',
    summary: 'Get countries',
    tags: ['Content'],
    parameters: [
        //ad name parameter
        new OA\Parameter(
            name: 'name',
            description: 'Search by category name',
            in: 'query',
            required: false,
        )
    ],
    responses  : [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(properties: [
                    new OA\Property(property: 'uuid', type: 'string'),
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'image', type: 'string'),
                    new OA\Property(property: 'recipes_count', type: 'integer'),
                ])),
            ],
            type      : 'object'
        )),
    ]
)]

class CategoryHandler extends Handler
{

    public function __construct(
        private readonly CategoryQueryInterface $categoryQuery,
    )
    {
    }

    public function __invoke(): JsonResponse
    {
        return $this->successResponseWithData(
            $this->categoryQuery->all()
        );
    }
}
