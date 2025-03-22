<?php

use App\Components\Auth\Infrastructure\ServiceProvider\AuthServiceProvider;
use App\Components\Content\Infrastructure\ServiceProvider\ContentServiceProvider;
use App\Components\MealPlanner\Infrastructure\ServiceProvider\MealPlannerServiceProvider;
use App\Components\Recipe\Infrastructure\ServiceProvider\RecipeServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    AuthServiceProvider::class,
    ContentServiceProvider::class,
    RecipeServiceProvider::class,
    MealPlannerServiceProvider::class,
];
