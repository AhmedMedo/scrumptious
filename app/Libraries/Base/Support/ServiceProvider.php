<?php

namespace App\Libraries\Base\Support;

use App\Libraries\Messaging\Infrastructure\MessageServiceProvider;
use Illuminate\Support\AggregateServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    /**
     * @return mixed[]
     */
    protected function regularBindings(): array
    {
        return [];
    }

    /**
     * @return mixed[]
     */
    protected function testingBindings(): array
    {
        return [];
    }

    public function register(): void
    {
        parent::register();

        $testingBindings = $this->app->runningUnitTests()
            ? $this->testingBindings()
            : [];
        $bindings = array_replace($this->regularBindings(), $testingBindings);

        foreach ($bindings as $abstract => $concrete) {
            $this->app->bind($abstract, $concrete);
        }
    }
}
