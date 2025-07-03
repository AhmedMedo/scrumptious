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
