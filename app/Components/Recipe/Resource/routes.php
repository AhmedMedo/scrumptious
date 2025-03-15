<?php

use App\Components\Recipe\Infrastructure\Http\Handler\Recipe\RecipeCreateHandler;
use App\Components\Recipe\Infrastructure\Http\Handler\Recipe\RecipeDeleteHandler;
use App\Components\Recipe\Infrastructure\Http\Handler\Recipe\RecipeUpdateHandler;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'recipe',
], function () {

    Route::post('/', RecipeCreateHandler::class);
    Route::patch('/{uuid}/update', RecipeUpdateHandler::class);
    Route::delete('/{uuid}/delete', RecipeDeleteHandler::class);

});


