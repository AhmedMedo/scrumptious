<?php

namespace App\Components\MealPlanner\Infrastructure\Query\Plan;

use App\Components\MealPlanner\Application\Query\Plan\PlanQueryInterface;
use App\Components\MealPlanner\Data\Entity\PlanEntity;
use Illuminate\Pagination\LengthAwarePaginator;

class PlanQuery implements PlanQueryInterface
{

    public function findBy(array $data): ?PlanEntity
    {
        return PlanEntity::query()->where($data)->firstOrFail();
    }

    public function paginate(string $userUuid): LengthAwarePaginator
    {
        return PlanEntity::query()
            ->with([
                'meals',
                'meals.recipes',
                'meals.recipes.ingredients',
                'meals.recipes.instructions',
                'meals.recipes.categories',
            ])
            ->where('user_uuid', $userUuid)
            ->latest()
            ->paginate();
    }
}
