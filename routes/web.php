<?php

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Subscription\Payments\Providers\Paymob\PaymobPayment;
use App\Http\Controllers\ScrumptiousPaymobController;
use Illuminate\Support\Facades\Route;
use MG\Paymob\Controllers\PaymobController;



Route::post('paymob/callback/processed', [ScrumptiousPaymobController::class, 'processed']);
Route::any('paymob/callback/response', [ScrumptiousPaymobController::class, 'response'])->name('paymob.response');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::withoutMiddleware(['web'])->group(function () {
    Route::get('/about-us', function () {
        return response(view('about-us'))
            ->header('Cache-Control', 'public, max-age=86400');
    })->name('about-us');

    Route::get('/privacy-policy', function () {
        return response(view('privacy-policy'))
            ->header('Cache-Control', 'public, max-age=86400');
    })->name('privacy-policy');

    Route::get('/terms-and-conditions', function () {
        return response(view('terms-and-conditions'))
            ->header('Cache-Control', 'public, max-age=86400');
    })->name('terms-and-conditions');
});
