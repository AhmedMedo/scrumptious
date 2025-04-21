<?php

namespace App\Components\MealPlanner\Application\Query\Plan;

use App\Components\MealPlanner\Data\Entity\PlanEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface PlanQueryInterface
{
    public function paginate(string $userUuid): LengthAwarePaginator;

    public function findBy(array $data): ?PlanEntity;
}
