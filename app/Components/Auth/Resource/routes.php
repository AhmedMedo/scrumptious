<?php

use App\Components\Auth\Infrastructure\Http\Handler\Authentication\ChangeMobileByEmailHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Authentication\EmailVerificationHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Authentication\ForgetPasswordHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Authentication\ForgetPasswordVerificationHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Authentication\GuestLoginHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Authentication\LoginHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Authentication\LogoutHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Authentication\ProfileHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Authentication\RegisterHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Authentication\ResendEmailVerificationHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Authentication\ResendOtpHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Authentication\ResetPasswordHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Authentication\UserVerificationHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Notifications\NotificationListHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Profile\ChangeEmailHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Profile\ChangePasswordHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Profile\ChangPhoneHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Profile\DeleteAccountHandler;
use App\Components\Auth\Infrastructure\Http\Handler\Profile\UpdateProfileHandler;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', LoginHandler::class);
    Route::post('login-as-guest', GuestLoginHandler::class);
    Route::post('register', RegisterHandler::class);
    Route::post('verification', UserVerificationHandler::class);
    Route::post('forget-password', ForgetPasswordHandler::class);
    Route::post('forget-password-verification', ForgetPasswordVerificationHandler::class);
    Route::post('reset-password', ResetPasswordHandler::class);
    Route::post('resend-otp', ResendOtpHandler::class);
    Route::post('verify-email', EmailVerificationHandler::class);
    Route::post('resend-email-verification', ResendEmailVerificationHandler::class);
    Route::post('change-mobile-by-email', ChangeMobileByEmailHandler::class);


    Route::group([
        'middleware' => ['auth:api']
    ], function () {
        Route::get('logout', LogoutHandler::class);
        Route::get('notifications', NotificationListHandler::class);
        Route::patch('update-profile', UpdateProfileHandler::class);
        Route::patch('change-password', ChangePasswordHandler::class);
        Route::get('profile', ProfileHandler::class);
        Route::delete('delete-account', DeleteAccountHandler::class);
        Route::patch('change-email',ChangeEmailHandler::class);
        Route::patch('change-phone', ChangPhoneHandler::class);
    });
});
