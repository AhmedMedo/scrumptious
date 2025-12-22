<?php

namespace App\Components\Recipe\Infrastructure\Http\Handler\Recipe;

use App\Components\Auth\Infrastructure\Service\UserService;
use App\Components\Recipe\Application\Mapper\Recipe\RecipeViewModelMapper;
use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use App\Components\Recipe\Data\Entity\RecipeEntity;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
#[OA\Get(
    path: '/api/v1/recipe/list',
    description: 'List of recipes',
    summary: 'List of recipes',
    tags: ['Recipe'],
    parameters: [
        new OA\Parameter(
            name: 'title',
            description: 'title of recipe',
            in: 'query',
            required: false,
            schema: new OA\Schema(type: 'string')
        ),
        new OA\Parameter(
            name: 'is_favorite',
            description: 'Return only favorite recipes in user favorite list',
            in: 'query',
            required: false,
            schema: new OA\Schema(type: 'boolean'),
            examples: [
                new OA\Examples(
                    example: 'true',
                    summary: 'Return only favorite recipes in user favorite list',
                ),
                new OA\Examples(
                    example: 'false',
                    summary: 'Return only non-favorite recipes in user favorite list',
                ),
            ]
        ),
        new OA\Parameter(
            name: 'is_admin',
            description: 'Return recipes created by admin when set to 1',
            in: 'query',
            required: false,
            schema: new OA\Schema(type: 'integer', enum: [0,1]),
            example: 1
        ),
        //Category
        new OA\Parameter(
            name: 'category',
            description: 'category of recipe',
            in: 'query',
            required: false,
            schema: new OA\Schema(type: 'string'),
            example: '2f4f7f8f-7f8f-8f8f-8f8f-8f8f8f8f8f8f'
        ),
        new OA\Parameter(
            name: 'per_page',
            description: 'Number of items per page',
            in: 'query',
            required: false,
            schema: new OA\Schema(type: 'integer')
        ),
        new OA\Parameter(
            name: 'is_popular',
            description: 'Return only popular recipes when set to true',
            in: 'query',
            required: false,
            schema: new OA\Schema(type: 'boolean'),
            examples: [
                new OA\Examples(
                    example: 'true',
                    summary: 'Return only popular recipes',
                ),
                new OA\Examples(
                    example: 'false',
                    summary: 'Return only non-popular recipes',
                ),
            ]
        ),
    ],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/RecipeViewModel', type: 'object')),
                new OA\Property(property: 'meta', ref: '#/components/schemas/Meta'),
            ],
            type      : 'object'
        )),
    ]
)]
class RecipeListHandler extends Handler
{


    public function __construct(
        private readonly RecipeServiceInterface $recipeService,
        private readonly RecipeViewModelMapper $recipeViewModelMapper,
        private readonly UserService $userService
    )
    {
    }


    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        $userUuid = null;
        if (($this->userService->isAuthenticated() && !$request->query('is_favorite') && !$request->query('is_admin')) || $request->has('full_recipes') ) {
            $user = $this->userService->user();
            $userUuid = $user->uuid();
        }
        $recipes = $this->recipeService->paginated(
            userUuid: $userUuid,
            withAdminRecipes: $request->has('full_recipes')
        );
        return $this->successResponseWithDataAndMeta(
            data: $recipes->map(fn (RecipeEntity $recipe) => $this->recipeViewModelMapper->fromEntity($recipe)->toArray())->toArray(),
            meta: [
                'total' => $recipes->total(),
                'per_page' => $recipes->perPage(),
                'current_page' => $recipes->currentPage(),
                'last_page' => $recipes->lastPage(),
            ]
        );
    }
}
