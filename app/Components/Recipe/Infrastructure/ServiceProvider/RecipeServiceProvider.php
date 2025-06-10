<?php

namespace App\Components\Recipe\Infrastructure\ServiceProvider;

use App\Components\Recipe\Application\Query\GroceryQueryInterface;
use App\Components\Recipe\Application\Query\GroceryCategoryQueryInterface;
use App\Components\Recipe\Application\Query\IngredientQueryInterface;
use App\Components\Recipe\Application\Query\RecipeQueryInterface;
use App\Components\Recipe\Application\Repository\IngredientRepositoryInterface;
use App\Components\Recipe\Application\Repository\RecipeRepositoryInterface;
use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use App\Components\Recipe\Infrastructure\Query\GroceryQuery;
use App\Components\Recipe\Infrastructure\Query\GroceryCategoryQuery;
use App\Components\Recipe\Infrastructure\Query\IngredientQuery;
use App\Components\Recipe\Infrastructure\Query\RecipeQuery;
use App\Components\Recipe\Infrastructure\Repository\IngredientRepository;
use App\Components\Recipe\Infrastructure\Repository\RecipeRepository;
use App\Components\Recipe\Infrastructure\Service\RecipeService;
use App\Libraries\Base\Support\ServiceProvider;

class RecipeServiceProvider extends ServiceProvider
{
    protected function regularBindings(): array
    {
        return [
            RecipeRepositoryInterface::class => RecipeRepository::class,
            RecipeQueryInterface::class => RecipeQuery::class,
            RecipeServiceInterface::class => RecipeService::class,
            IngredientRepositoryInterface::class => IngredientRepository::class,
            IngredientQueryInterface::class => IngredientQuery::class,
            GroceryQueryInterface::class => GroceryQuery::class,
            GroceryCategoryQueryInterface::class => GroceryCategoryQuery::class,
        ];
    }
}
