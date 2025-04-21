<?php

namespace App\Components\MealPlanner\Infrastructure\Repository\Plan;

use App\Components\MealPlanner\Application\Query\Plan\PlanQueryInterface;
use App\Components\MealPlanner\Application\Repository\Plan\PlanRepositoryInterface;
use App\Components\MealPlanner\Data\Entity\PlanEntity;
use Illuminate\Support\Arr;

class PlanRepository implements PlanRepositoryInterface
{
    public function __construct(
        private readonly PlanQueryInterface $query
    )
    {
    }

    public function create(array $data): void
    {
       $plan = PlanEntity::query()->create([
           'user_uuid' => Arr::get($data, 'user_uuid'),
           'type' => Arr::get($data, 'type'),
           'start_date' => Arr::get($data, 'start_date'),
           'end_date' => Arr::get($data, 'end_date'),
       ]);

       foreach ($data['meals'] as $meal)
       {
          $mealEntity =  $plan->meals()->create([
               'type' => Arr::get($meal, 'type'),
           ]);

           $mealEntity->recipes()->attach(Arr::get($meal, 'recipes'));
       }
    }



    public function update(string $uuid, array $data):void
    {
        $plan = $this->query->findBy(['uuid' => $uuid]);
        $plan->update([
            'type' => Arr::get($data, 'type'),
            'start_date' => Arr::get($data, 'start_date'),
            'end_date' => Arr::get($data, 'end_date'),
        ]);

        $plan->meals()->delete();

        foreach ($data['meals'] as $meal)
        {
           $mealEntity =  $plan->meals()->create([
                'type' => Arr::get($meal, 'type'),
            ]);

            $mealEntity->recipes()->attach(Arr::get($meal, 'recipes'));
        }

    }

    public function delete(string $uuid): void
    {
        $plan = $this->query->findBy(['uuid' => $uuid]);
        $plan->meals()->delete();
        $plan->delete();
    }
}
