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

Route::get('/about-us', function () {
    return view('about-us');
})->name('about-us');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('/terms-and-conditions', function () {
    return view('terms-and-conditions');
})->name('terms-and-conditions');
