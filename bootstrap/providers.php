<?php

use App\Components\Content\Infrastructure\ServiceProvider\ContentServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    App\Components\Auth\Infrastructure\ServiceProvider\AuthServiceProvider::class,
    ContentServiceProvider::class,
    \App\Components\Recipe\Infrastructure\ServiceProvider\RecipeServiceProvider::class,
];
