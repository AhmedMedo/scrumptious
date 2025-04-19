<?php

namespace App\Components\Recipe\Infrastructure\Http\Handler\Ingredient;

use App\Components\Recipe\Application\Query\IngredientQueryInterface;
use App\Components\Recipe\Data\Entity\IngredientEntity;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/ingredients',
    description: 'List ingredients',
    summary: 'List ingredients',
    tags: ['Ingredient'],
    parameters: [
        new OA\Parameter(
            name: 'content',
            description: 'content of ingredient',
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
                ])),
                new OA\Property(property: 'meta', ref: '#/components/schemas/Meta'),
            ]
        ))
    ]
)]
class IngredientListHandler extends Handler
{

    public function __construct(
        private readonly IngredientQueryInterface $ingredientQuery
    )
    {
    }

    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $ingredients = $this->ingredientQuery->paginated();
        return $this->successResponseWithDataAndMeta(
            data: $ingredients->map(fn (IngredientEntity $ingredient) => [
                'uuid' => $ingredient->uuid,
                'content' => $ingredient->content,
            ])->toArray(),
            meta: [
                'total' => $ingredients->total(),
                'per_page' => $ingredients->perPage(),
                'current_page' => $ingredients->currentPage(),
                'last_page' => $ingredients->lastPage(),
            ]
        );
    }
}
