<?php

namespace App\Components\MealPlanner\Infrastructure\Repository\Breakdown;

use App\Components\MealPlanner\Application\Query\Breakdown\BreakdownQueryInterface;
use App\Components\MealPlanner\Application\Repository\Breakdown\BreakdownRepositoryInterface;
use App\Components\MealPlanner\Data\Entity\MealPlanBreakdownEntity;
use Illuminate\Support\Arr;

class BreakdownRepository implements BreakdownRepositoryInterface
{
    public function __construct(
        private readonly BreakdownQueryInterface $query
    )
    {
    }

    public function create(array $data): void
    {
        $planUuid = Arr::get($data, 'plan_uuid');
        
        $breakdown = MealPlanBreakdownEntity::query()->create([
            'plan_uuid' => $planUuid,
            'date' => Arr::get($data, 'date'),
        ]);

        foreach ($data['meals'] as $meal) {
            $mealEntity = $breakdown->meals()->create([
                'type' => Arr::get($meal, 'type'),
                'plan_uuid' => $planUuid, // Also save plan_uuid for backward compatibility
            ]);

            $mealEntity->recipes()->attach(Arr::get($meal, 'recipes'));
        }
    }

    public function update(string $uuid, array $data): void
    {
        $breakdown = $this->query->findBy(['uuid' => $uuid]);
        $planUuid = $breakdown->plan_uuid; // Get plan_uuid from breakdown
        
        $breakdown->update([
            'date' => Arr::get($data, 'date'),
        ]);

        $breakdown->meals()->delete();

        foreach ($data['meals'] as $meal) {
            $mealEntity = $breakdown->meals()->create([
                'type' => Arr::get($meal, 'type'),
                'plan_uuid' => $planUuid, // Also save plan_uuid for backward compatibility
            ]);

            $mealEntity->recipes()->attach(Arr::get($meal, 'recipes'));
        }
    }

    public function delete(string $uuid): void
    {
        $breakdown = $this->query->findBy(['uuid' => $uuid]);
        $breakdown->meals()->delete();
        $breakdown->delete();
    }
}
