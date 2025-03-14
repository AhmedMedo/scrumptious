<?php

use App\Components\Content\Infrastructure\Http\Handler\CategoryHandler;
use App\Components\Content\Infrastructure\Http\Handler\ConfigHandler;
use App\Components\Content\Infrastructure\Http\Handler\CountryHandler;
use App\Components\Content\Infrastructure\Http\Handler\CustomerSupportHandler;
use App\Components\Content\Infrastructure\Http\Handler\FaqHandler;
use App\Components\Content\Infrastructure\Http\Handler\NewsletterHandler;
use App\Components\Content\Infrastructure\Http\Handler\UploadMediaHandler;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'content'
], function () {
    Route::get('countries', CountryHandler::class);
    Route::post('upload-media', UploadMediaHandler::class);
    Route::get('config', ConfigHandler::class);
    Route::get('faq', FaqHandler::class);
    Route::get('categories', CategoryHandler::class);
});

Route::post('customer-support', CustomerSupportHandler::class);
Route::post('newsletter', NewsletterHandler::class);

