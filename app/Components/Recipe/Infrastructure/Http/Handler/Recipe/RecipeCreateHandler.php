<?php

namespace App\Components\Recipe\Infrastructure\Http\Handler\Recipe;

use App\Components\Auth\Infrastructure\Service\UserService;
use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use App\Components\Recipe\Infrastructure\Http\Request\Recipe\CreateRecipeRequest;
use App\Libraries\Base\Database\MySQL\ConnectionService;
use App\Libraries\Base\Http\Handler;
use Exception;
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
        private readonly RecipeServiceInterface $recipeService,
        private readonly UserService            $userService,
        private readonly ConnectionService      $connection
    )
    {
    }

    public function __invoke(CreateRecipeRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->connection->beginTransaction();
            $user = $this->userService->user();
            $this->recipeService->store(array_merge($request->validated(), ['user_uuid' => $user->uuid()]));
            $this->connection->commit();

            return $this->successResponseWithMessage('Recipe created successfully');
        } catch (Exception $exception) {

            $this->connection->rollBack();

            return $this->errorResponseWithData([], $exception->getMessage());
        }
    }
}
