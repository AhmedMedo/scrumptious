<?php

use App\Components\MealPlanner\Infrastructure\Http\Handler\Plan\PlanCreateHandler;
use App\Components\MealPlanner\Infrastructure\Http\Handler\Plan\PlanDeleteHandler;
use App\Components\MealPlanner\Infrastructure\Http\Handler\Plan\PlanListHandler;
use App\Components\MealPlanner\Infrastructure\Http\Handler\Plan\PlanShowHandler;
use App\Components\MealPlanner\Infrastructure\Http\Handler\Plan\PlanUpdateHandler;
use App\Components\MealPlanner\Infrastructure\Http\Handler\Target\TargetCreateHandler;
use App\Components\MealPlanner\Infrastructure\Http\Handler\Target\TargetDeleteHandler;
use App\Components\MealPlanner\Infrastructure\Http\Handler\Target\TargetListHandler;
use App\Components\MealPlanner\Infrastructure\Http\Handler\Target\TargetShowHandler;
use App\Components\MealPlanner\Infrastructure\Http\Handler\Target\TargetUpdateHandler;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'target',
    'middleware' => ['auth:api']
], function () {

    Route::get('/{uuid}/show', TargetShowHandler::class);
    Route::get('/list', TargetListHandler::class);
    Route::post('/', TargetCreateHandler::class);
    Route::patch('/{uuid}/update', TargetUpdateHandler::class);
    Route::delete('/{uuid}/delete', TargetDeleteHandler::class);
});



Route::group([
    'prefix' => 'plans',
    'middleware' => ['auth:api']
], function () {
    Route::post('/', PlanCreateHandler::class);
    Route::patch('/{uuid}/update', PlanUpdateHandler::class);
    Route::delete('/{uuid}/delete', PlanDeleteHandler::class);
    Route::get('/list', PlanListHandler::class);
    Route::get('/{uuid}/show', PlanShowHandler::class);
});
