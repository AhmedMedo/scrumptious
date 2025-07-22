<?php

use App\Components\Recipe\Infrastructure\Http\Handler\Ingredient\IngredientListHandler;
use App\Components\Recipe\Infrastructure\Http\Handler\Recipe\RecipeCreateHandler;
use App\Components\Recipe\Infrastructure\Http\Handler\Recipe\RecipeDeleteHandler;
use App\Components\Recipe\Infrastructure\Http\Handler\Recipe\RecipeListHandler;
use App\Components\Recipe\Infrastructure\Http\Handler\Recipe\RecipeShowHandler;
use App\Components\Recipe\Infrastructure\Http\Handler\Recipe\RecipeToggleFavouriteHandler;
use App\Components\Recipe\Infrastructure\Http\Handler\Recipe\RecipeUpdateHandler;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'recipe',
], function () {

    Route::get('/{uuid}/show',RecipeShowHandler::class);
    Route::get('/list',RecipeListHandler::class);
    Route::post('/', RecipeCreateHandler::class)->middleware('auth:api');;
    Route::patch('/{uuid}/update', RecipeUpdateHandler::class);
    Route::delete('/{uuid}/delete', RecipeDeleteHandler::class);
    Route::post('/{uuid}/toggle-favourite', RecipeToggleFavouriteHandler::class)->middleware('auth:api');
});

Route::group([
    'prefix' => 'ingredients',
], function () {
    Route::get('/', IngredientListHandler::class);
});




Route::group([
    'prefix' => 'groceries',
], function () {
    Route::get('/', \App\Components\Recipe\Infrastructure\Http\Handler\Grocery\GroceryListHandler::class);
});
