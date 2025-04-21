<?php

namespace App\Components\MealPlanner\Infrastructure\ServiceProvider;

use App\Components\MealPlanner\Application\Query\Plan\PlanQueryInterface;
use App\Components\MealPlanner\Application\Query\Target\TargetQueryInterface;
use App\Components\MealPlanner\Application\Repository\Plan\PlanRepositoryInterface;
use App\Components\MealPlanner\Application\Repository\Target\TargetRepositoryInterface;
use App\Components\MealPlanner\Application\Service\Plan\PlanServiceInterface;
use App\Components\MealPlanner\Application\Service\Target\TargetServiceInterface;
use App\Components\MealPlanner\Infrastructure\Query\Plan\PlanQuery;
use App\Components\MealPlanner\Infrastructure\Query\Target\TargetQuery;
use App\Components\MealPlanner\Infrastructure\Repository\Plan\PlanRepository;
use App\Components\MealPlanner\Infrastructure\Repository\Target\TargetRepository;
use App\Components\MealPlanner\Infrastructure\Service\Plan\PlanService;
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

            //Plan

            PlanServiceInterface::class => PlanService::class,
            PlanRepositoryInterface::class => PlanRepository::class,
            PlanQueryInterface::class => PlanQuery::class,
        ];
    }
}
