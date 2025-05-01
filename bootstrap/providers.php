<?php

return [
    App\Components\Auth\Infrastructure\ServiceProvider\AuthServiceProvider::class,
    App\Components\Content\Infrastructure\ServiceProvider\ContentServiceProvider::class,
    App\Components\MealPlanner\Infrastructure\ServiceProvider\MealPlannerServiceProvider::class,
    App\Components\Recipe\Infrastructure\ServiceProvider\RecipeServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    EloquentFilter\ServiceProvider::class,
];
