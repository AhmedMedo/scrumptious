<?php

namespace App\Components\Recipe\Infrastructure\Http\Handler\Recipe;

use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use App\Libraries\Base\Http\Handler;

class RecipeListHandler extends Handler
{


    public function __construct(
        private readonly RecipeServiceInterface $recipeService
    )
    {
    }


    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $recipes = $this->recipeService->all();
        return $this->successResponseWithDataAndMeta($recipes);
    }
}
