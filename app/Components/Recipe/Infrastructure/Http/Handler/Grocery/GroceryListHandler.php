<?php

namespace App\Components\Recipe\Infrastructure\Http\Handler\Grocery;

use App\Components\Recipe\Application\Query\GroceryQueryInterface;
use App\Components\Recipe\Application\Query\IngredientQueryInterface;
use App\Components\Recipe\Data\Entity\GroceryEntity;
use App\Components\Recipe\Data\Entity\IngredientEntity;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/groceries',
    description: 'List groceries',
    summary: 'List groceries',
    tags: ['Grocery'],
    parameters: [
        new OA\Parameter(
            name: 'content',
            description: 'content of grocery',
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
                    new OA\Property(property: 'content', type: 'string'),
                    new OA\Property(property: 'image', type: 'string'),
                ])),
                new OA\Property(property: 'meta', ref: '#/components/schemas/Meta'),
            ]
        ))
    ]
)]
class GroceryListHandler extends Handler
{

    public function __construct(
        private readonly GroceryQueryInterface $groceryQuery
    )
    {
    }

    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $groceries = $this->groceryQuery->paginated();
        return $this->successResponseWithDataAndMeta(
            data: $groceries->map(fn (GroceryEntity $groceryEntity) => [
                'uuid' => $groceryEntity->uuid,
                'content' => $groceryEntity->content,
                'image' => $groceryEntity->getFirstMediaUrl('image'),
            ])->toArray(),
            meta: [
                'total' => $groceries->total(),
                'per_page' => $groceries->perPage(),
                'current_page' => $groceries->currentPage(),
                'last_page' => $groceries->lastPage(),
            ]
        );
    }
}
