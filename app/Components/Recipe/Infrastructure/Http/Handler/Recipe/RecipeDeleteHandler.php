<?php

namespace App\Components\Recipe\Infrastructure\Http\Handler\Recipe;

use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Delete(
    path: '/api/v1/recipe/{uuid}/delete',
    description: 'delete recipe',
    summary: 'delete recipe',
    tags: ['Recipe'],
    parameters: [
        new OA\Parameter(name: 'uuid', description: 'Recipe  UUID', in: 'path', required: true),
    ],
    responses: [

        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
            ],
            type: 'object',
        ))
    ]
)]
class RecipeDeleteHandler extends Handler
{
    public function __construct(
        private readonly RecipeServiceInterface $recipeService
    )
    {
    }

    public function __invoke(string $uuid): \Illuminate\Http\JsonResponse
    {
        $this->recipeService->delete($uuid);
        return $this->successResponseWithMessage('Recipe deleted successfully');
    }
}
