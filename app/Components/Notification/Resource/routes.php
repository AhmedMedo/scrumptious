<?php

use App\Components\Notification\Infrastructure\Http\Handler\DeleteNotificationHandler;
use App\Components\Notification\Infrastructure\Http\Handler\GetNotificationsHandler;
use App\Components\Notification\Infrastructure\Http\Handler\GetUnreadCountHandler;
use App\Components\Notification\Infrastructure\Http\Handler\MarkAllAsReadHandler;
use App\Components\Notification\Infrastructure\Http\Handler\MarkAsReadHandler;
use App\Components\Notification\Infrastructure\Http\Handler\RegisterDeviceTokenHandler;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'notifications',
    'middleware' => ['auth:api']
], function () {
    Route::post('register-device', RegisterDeviceTokenHandler::class);
    Route::get('/', GetNotificationsHandler::class);
    Route::get('unread-count', GetUnreadCountHandler::class);
    Route::patch('{uuid}/read', MarkAsReadHandler::class);
    Route::patch('read-all', MarkAllAsReadHandler::class);
    Route::delete('{uuid}', DeleteNotificationHandler::class);
});
