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
    'prefix' => 'subscription',
], function () {
    Route::get('/plans', \App\Components\Subscription\Infrastructure\Http\Handler\SubscriptionPlan\PlanListHandler::class);
    Route::post('/payment', \App\Components\Subscription\Infrastructure\Http\Handler\Payment\PaymentHandler::class);
});
