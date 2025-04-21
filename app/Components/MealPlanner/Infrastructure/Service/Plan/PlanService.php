<?php

namespace App\Components\MealPlanner\Infrastructure\Service\Plan;

use App\Components\MealPlanner\Application\Query\Plan\PlanQueryInterface;
use App\Components\MealPlanner\Application\Repository\Plan\PlanRepositoryInterface;
use App\Components\MealPlanner\Application\Service\Plan\PlanServiceInterface;
use App\Components\MealPlanner\Data\Entity\PlanEntity;
use Illuminate\Pagination\LengthAwarePaginator;

class PlanService implements PlanServiceInterface
{
    public function __construct(
        private readonly PlanRepositoryInterface $planRepository,
        private readonly PlanQueryInterface $planQuery
    )
    {
    }

    public function store(array $data): void
    {
         $this->planRepository->create($data);
    }


    public function update(string $uuid, array $data): void
    {
        $this->planRepository->update($uuid, $data);
    }

    public function paginate(string $userUuid): LengthAwarePaginator
    {
        return $this->planQuery->paginate($userUuid);
    }

    public function delete(string $uuid): void
    {
        $this->planRepository->delete($uuid);
    }

    public function show(string $uuid): ?PlanEntity
    {
        return $this->planQuery->findBy(['uuid' => $uuid]);
    }
}
