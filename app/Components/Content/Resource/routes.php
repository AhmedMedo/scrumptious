<?php

use App\Components\Content\Infrastructure\Http\Handler\BrandsHandler;
use App\Components\Content\Infrastructure\Http\Handler\ConfigHandler;
use App\Components\Content\Infrastructure\Http\Handler\CountryHandler;
use App\Components\Content\Infrastructure\Http\Handler\CustomerSupportHandler;
use App\Components\Content\Infrastructure\Http\Handler\FaqHandler;
use App\Components\Content\Infrastructure\Http\Handler\NewsletterHandler;
use App\Components\Content\Infrastructure\Http\Handler\PartnersHandler;
use App\Components\Content\Infrastructure\Http\Handler\UploadMediaHandler;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'content'
], function () {
    Route::get('countries', CountryHandler::class);
    Route::post('upload-media', UploadMediaHandler::class);
    Route::get('config', ConfigHandler::class);
    Route::get('faq', FaqHandler::class);
    Route::get('brands', BrandsHandler::class);


});
Route::post('partners', PartnersHandler::class);
Route::post('customer-support', CustomerSupportHandler::class);
Route::post('newsletter', NewsletterHandler::class);

