<?php

namespace App\Libraries\Support\Provider;

use Illuminate\Http\Response;
use Illuminate\Support\AggregateServiceProvider;
use Illuminate\Support\Facades\Route;

abstract class ComponentServiceProvider extends AggregateServiceProvider
{
    protected array $services = [];
    protected array $queries = [];
    protected array $repositories = [];
    protected array $factories = [];

    public function boot(): void
    {
        $this->bindQueries();
        $this->bindRepositories();
        $this->bindServices();
        $this->bindFactories();
    }

    private function bindServices(): void
    {
        foreach ($this->services as $contract => $service) {
            $this->app->bind($contract, $service);
        }
    }

    private function bindQueries(): void
    {
        foreach ($this->queries as $contract => $query) {
            $this->app->bind($contract, $query);
        }
    }

    private function bindRepositories(): void
    {
        foreach ($this->repositories as $contract => $repository) {
            $this->app->bind($contract, $repository);
        }
    }

    private function bindFactories(): void
    {
        foreach ($this->factories as $contract => $factory) {
            $this->app->bind($contract, $factory);
        }
    }
}
