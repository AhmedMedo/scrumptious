<?php

namespace App\Components\MealPlanner\Infrastructure\Service\Breakdown;

use App\Components\MealPlanner\Application\Query\Breakdown\BreakdownQueryInterface;
use App\Components\MealPlanner\Application\Repository\Breakdown\BreakdownRepositoryInterface;
use App\Components\MealPlanner\Application\Service\Breakdown\BreakdownServiceInterface;
use App\Components\MealPlanner\Data\Entity\MealPlanBreakdownEntity;
use App\Components\MealPlanner\Data\Entity\PlanEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class BreakdownService implements BreakdownServiceInterface
{
    public function __construct(
        private readonly BreakdownRepositoryInterface $breakdownRepository,
        private readonly BreakdownQueryInterface $breakdownQuery
    )
    {
    }

    public function store(array $data, string $userUuid): MealPlanBreakdownEntity
    {
        // Get latest active plan for the user
        $plan = PlanEntity::query()
            ->where('user_uuid', $userUuid)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (!$plan) {
            throw new \Exception('No active plan found for the user.');
        }

        // Validate date is within plan range
        $date = \Carbon\Carbon::parse(Arr::get($data, 'date'));
        if ($date < $plan->start_date || $date > $plan->end_date) {
            throw new \Exception('The date must be within the plan\'s start date (' . $plan->start_date->format('Y-m-d') . ') and end date (' . $plan->end_date->format('Y-m-d') . ').');
        }

        // Add plan_uuid to data
        $data['plan_uuid'] = $plan->uuid;

        return $this->breakdownRepository->create($data);
    }

    public function update(string $uuid, array $data): MealPlanBreakdownEntity
    {
        return $this->breakdownRepository->update($uuid, $data);
    }

    public function delete(string $uuid): void
    {
        $this->breakdownRepository->delete($uuid);
    }

    public function show(string $uuid): ?MealPlanBreakdownEntity
    {
        return $this->breakdownQuery->findBy(['uuid' => $uuid]);
    }

    public function paginate(?string $planUuid = null, ?string $date = null): LengthAwarePaginator
    {
        return $this->breakdownQuery->paginate($planUuid, $date);
    }
}

