<?php

namespace App\Components\Recipe\Infrastructure\ServiceProvider;

use App\Components\Recipe\Application\Query\RecipeQueryInterface;
use App\Components\Recipe\Application\Repository\RecipeRepositoryInterface;
use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use App\Components\Recipe\Infrastructure\Query\RecipeQuery;
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
        ];
    }
}
