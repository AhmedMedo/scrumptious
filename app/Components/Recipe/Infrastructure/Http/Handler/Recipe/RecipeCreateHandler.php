<?php

namespace App\Components\Recipe\Infrastructure\Http\Handler\Recipe;

use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use App\Components\Recipe\Infrastructure\Http\Request\Recipe\CreateRecipeRequest;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/v1/recipe',
    description: 'Create new recipe',
    summary: 'Create new recipe',
    requestBody: new OA\RequestBody('#/components/requestBodies/CreateRecipeRequest'),
    tags: ['Recipe'],
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

class RecipeCreateHandler extends Handler
{

    public function __construct(
        private readonly RecipeServiceInterface $recipeService
    )
    {
    }

    public function __invoke(CreateRecipeRequest $request): \Illuminate\Http\JsonResponse
    {
        $recipe = $this->recipeService->store($request->validated());
        return $this->successResponseWithMessage('Recipe created successfully');
    }
}
