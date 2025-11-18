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
    description: 'List groceries (non-paginated)',
    summary: 'List groceries (no pagination)',
    tags: ['Grocery'],
    parameters: [
        new OA\Parameter(
            name: 'content',
            description: 'content of grocery',
            in: 'query',
            required: false,
        ),
        new OA\Parameter(
            name: 'category_uuid',
            description: 'filter by grocery category uuid',
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
                    new OA\Property(property: 'category', properties: [
                        new OA\Property(property: 'uuid', type: 'string'),
                        new OA\Property(property: 'name', type: 'string'),
                        new OA\Property(property: 'image', type: 'string'),
                    ], type: 'object')
                ])),
                // meta removed for non-paginated response
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
        $groceries = $this->groceryQuery->all();

        return $this->successResponseWithData(
            data: $groceries->map(fn (GroceryEntity $groceryEntity) => [
                'uuid' => $groceryEntity->uuid,
                'content' => $groceryEntity->content,
                'image' => $groceryEntity->getFirstMediaUrl('image'),
                'category' => $groceryEntity->category? [
                    'uuid' => $groceryEntity->category->uuid,
                    'name' => $groceryEntity->category->name,
                    'image' => $groceryEntity->category->getFirstMediaUrl('image'),
                ]: null
            ])->toArray()
        );
    }
}
