<?php

namespace App\Components\Recipe\Infrastructure\Http\Handler\Recipe;

use App\Components\Recipe\Application\Mapper\Recipe\RecipeViewModelMapper;
use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use App\Components\Recipe\Data\Entity\RecipeEntity;
use App\Components\Recipe\Presentation\ViewModel\RecipeViewModel;
use App\Libraries\Base\Http\Handler;

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
