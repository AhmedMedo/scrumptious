<?php

namespace App\Components\Recipe\Infrastructure\Http\Handler\Recipe;

use App\Components\Recipe\Application\Mapper\Recipe\RecipeViewModelMapper;
use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/recipe/{uuid}/show',
    summary: 'Show Recipe',
    tags: ['Recipe'],
    parameters: [
        new OA\Parameter(
            name: 'uuid',
            in: 'path',
            required: true
        )
    ],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', ref: '#/components/schemas/RecipeViewModel'),
            ],
            type      : 'object'
        )),
    ]

)]

class RecipeShowHandler extends Handler
{
    public function __construct(
        private readonly RecipeServiceInterface $recipeService,
        private readonly RecipeViewModelMapper $recipeViewModelMapper
    )
    {
    }

    public function __invoke(string $uuid): \Illuminate\Http\JsonResponse
    {
        $recipe = $this->recipeService->findByUuid($uuid);
        return $this->successResponseWithData(
            data: $this->recipeViewModelMapper->fromEntity($recipe)->toArray()
        );
    }

}
