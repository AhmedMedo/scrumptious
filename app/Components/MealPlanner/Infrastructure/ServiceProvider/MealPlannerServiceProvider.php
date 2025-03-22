<?php

namespace App\Components\MealPlanner\Infrastructure\ServiceProvider;

use App\Components\MealPlanner\Application\Query\Target\TargetQueryInterface;
use App\Components\MealPlanner\Application\Repository\Target\TargetRepositoryInterface;
use App\Components\MealPlanner\Application\Service\Target\TargetServiceInterface;
use App\Components\MealPlanner\Infrastructure\Query\Target\TargetQuery;
use App\Components\MealPlanner\Infrastructure\Repository\Target\TargetRepository;
use App\Components\MealPlanner\Infrastructure\Service\Target\TargetService;
use App\Libraries\Base\Support\ServiceProvider;

class MealPlannerServiceProvider extends ServiceProvider
{
    protected function regularBindings(): array
    {
        return [
            TargetServiceInterface::class => TargetService::class,
            TargetRepositoryInterface::class => TargetRepository::class,
            TargetQueryInterface::class => TargetQuery::class,
        ];
    }
}
