<?php

namespace App\Components\Recipe\Infrastructure\Http\Handler\Recipe;

use App\Components\Recipe\Application\Mapper\Recipe\RecipeViewModelMapper;
use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use App\Components\Recipe\Data\Entity\RecipeEntity;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;


#[OA\Get(
    path: '/api/v1/recipe/list',
    summary: 'List of recipes',
    tags: ['Recipe'],
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
        private readonly RecipeViewModelMapper $recipeViewModelMapper
    )
    {
    }


    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $recipes = $this->recipeService->paginated();
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
