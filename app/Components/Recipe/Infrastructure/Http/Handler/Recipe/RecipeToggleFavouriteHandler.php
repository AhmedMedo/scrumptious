<?php

namespace App\Components\Recipe\Infrastructure\Http\Handler\Recipe;
use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/v1/recipe/{uuid}/toggle-favourite',
    description: 'Toggle favourite recipe',
    summary: 'Toggle favourite recipe',
    tags: ['Recipe'],
    parameters: [
        new OA\Parameter(
            name: 'uuid',
            description: 'Recipe uuid',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'string')
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'success ',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string'),
                    new OA\Property(property: 'message', type: 'string'),
                ],
                type: 'object',
            )
        )
    ]
)]

class RecipeToggleFavouriteHandler extends Handler
{

    public function __construct(
        private readonly RecipeServiceInterface $recipeService
    )
    {
    }

    public function __invoke(string $uuid): \Illuminate\Http\JsonResponse
    {
        $this->recipeService->toggleFavourite($uuid);
        return $this->successResponseWithMessage('Recipe toggled successfully');
    }


}
