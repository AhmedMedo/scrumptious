<?php

namespace App\Components\MealPlanner\Infrastructure\Query\Breakdown;

use App\Components\MealPlanner\Application\Query\Breakdown\BreakdownQueryInterface;
use App\Components\MealPlanner\Data\Entity\MealPlanBreakdownEntity;
use Illuminate\Pagination\LengthAwarePaginator;

class BreakdownQuery implements BreakdownQueryInterface
{
    public function findBy(array $data): ?MealPlanBreakdownEntity
    {
        return MealPlanBreakdownEntity::query()
            ->with([
                'meals',
                'meals.recipes',
                'meals.recipes.ingredients',
                'meals.recipes.instructions',
                'meals.recipes.categories',
                'plan', // Only load plan, no need for plan.meals
            ])
            ->where($data)
            ->first();
    }

    public function findByPlanAndDate(string $planUuid, string $date): ?MealPlanBreakdownEntity
    {
        return MealPlanBreakdownEntity::query()
            ->with([
                'meals',
                'meals.recipes',
                'meals.recipes.ingredients',
                'meals.recipes.instructions',
                'meals.recipes.categories',
                'plan', // Only load plan, no need for plan.meals
            ])
            ->where('plan_uuid', $planUuid)
            ->whereDate('date', $date)
            ->first();
    }

    public function paginate(?string $planUuid = null, ?string $date = null): LengthAwarePaginator
    {
        $query = MealPlanBreakdownEntity::query()
            ->with([
                'meals',
                'meals.recipes',
                'meals.recipes.ingredients',
                'meals.recipes.instructions',
                'meals.recipes.categories',
                'plan', // Only load plan, no need for plan.meals
            ]);

        if ($planUuid) {
            $query->where('plan_uuid', $planUuid);
        }

        if ($date) {
            $query->whereDate('date', $date);
        }

        return $query->latest()->paginate();
    }
}
