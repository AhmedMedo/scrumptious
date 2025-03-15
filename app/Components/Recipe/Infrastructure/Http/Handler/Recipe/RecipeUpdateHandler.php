<?php

namespace App\Components\Recipe\Infrastructure\Http\Handler\Recipe;

use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use App\Components\Recipe\Infrastructure\Http\Request\Recipe\UpdateRecipeRequest;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;


#[OA\Patch(
    path: '/api/v1/recipe/{uuid}/update',
    description: 'update recipe',
    summary: 'update recipe',
    requestBody: new OA\RequestBody('#/components/requestBodies/CreateRecipeRequest'),
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

class RecipeUpdateHandler extends Handler
{


    public function __construct(
        private readonly RecipeServiceInterface $recipeService
    )
    {
    }

    public function __invoke(UpdateRecipeRequest $request, string $uuid): \Illuminate\Http\JsonResponse
    {
        $recipe = $this->recipeService->update($uuid, $request->validated());
        return $this->successResponseWithMessage('Recipe updated successfully');
    }
}
