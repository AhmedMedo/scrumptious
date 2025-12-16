<?php

namespace App\Components\MealPlanner\Application\Query\Breakdown;

use App\Components\MealPlanner\Data\Entity\MealPlanBreakdownEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface BreakdownQueryInterface
{
    public function findBy(array $data): ?MealPlanBreakdownEntity;

    public function findByPlanAndDate(string $planUuid, string $date): ?MealPlanBreakdownEntity;

    public function paginate(?string $planUuid = null, ?string $date = null): LengthAwarePaginator;
}

