<?php

namespace App\Components\Content\Infrastructure\Http\Handler;

use App\Components\Recipe\Application\Query\GroceryCategoryQueryInterface;
use App\Components\Recipe\Data\Entity\GroceryCategoryEntity;
use App\Components\Recipe\Data\Entity\GroceryEntity;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/grocery-categories',
    description: 'List grocery categories',
    summary: 'List grocery categories',
    tags: ['Content'],
    parameters: [
        new OA\Parameter(
            name: 'name',
            description: 'Search by category name',
            in: 'query',
            required: false,
        )
    ],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(properties: [
                    new OA\Property(property: 'uuid', type: 'string'),
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'image', type: 'string'),
                ])),
                new OA\Property(property: 'meta', ref: '#/components/schemas/Meta'),
            ],
            type: 'object'
        )),
    ]
)]
class GroceryCategoryHandler extends Handler
{
    public function __construct(private readonly GroceryCategoryQueryInterface $query)
    {
    }

    public function __invoke(): JsonResponse
    {
        $categories = $this->query->paginated();
        return $this->successResponseWithDataAndMeta(
            data: $categories->map(fn (GroceryCategoryEntity $category) => [
                'uuid' => $category->uuid,
                'name' => $category->name,
                'image' => $category->getFirstMediaUrl('image'),
                'groceries' => $category?->groceries->map(fn (GroceryEntity $grocery) => [
                    'uuid' => $grocery->uuid,
                    'content' => $grocery->content,
                ]),
            ])->toArray(),
            meta: [
                'total' => $categories->total(),
                'per_page' => $categories->perPage(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
            ]
        );
    }
}
