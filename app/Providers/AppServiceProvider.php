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
        //
    }
}
