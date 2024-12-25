<?php

namespace App\Libraries\Support\GoogleApi;

use GuzzleHttp\Client;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class GoogleApiServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return array<class-string<GoogleApiClient>>
     */
    public function provides(): array
    {
        return [GoogleApiClient::class];
    }

    public function register(): void
    {
        $this->app->bind(
            GoogleApiClient::class,
            fn (Application $app): GoogleApiClient => new GoogleApiClient(
                new Client(),
                config('telgani.GOOGLE_API')
            )
        );
    }
}
