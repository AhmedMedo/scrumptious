<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client as TwilioClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(TwilioClient::class, function ($app): TwilioClient {
            return new TwilioClient(env('TWILIO_ACCOUNT_SID','XXXX'),env('TWILIO_AUTH_TOKEN','XXXXX'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Event::listen(
            \App\Components\Notification\Infrastructure\Events\MealPlanCustomizedEvent::class,
            \App\Components\Notification\Infrastructure\Listeners\SendMealPlanCustomizedNotification::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Components\Notification\Infrastructure\Events\TargetReminderEvent::class,
            \App\Components\Notification\Infrastructure\Listeners\SendTargetReminderNotification::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Components\Notification\Infrastructure\Events\NewRecipeUploadedEvent::class,
            \App\Components\Notification\Infrastructure\Listeners\SendNewRecipeNotification::class
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Components\Notification\Infrastructure\Events\AdminBroadcastEvent::class,
            \App\Components\Notification\Infrastructure\Listeners\SendAdminBroadcastNotification::class
        );
    }
}
