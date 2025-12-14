<?php

namespace App\Components\MealPlanner\Infrastructure\Service\Breakdown;

use App\Components\MealPlanner\Application\Query\Breakdown\BreakdownQueryInterface;
use App\Components\MealPlanner\Application\Repository\Breakdown\BreakdownRepositoryInterface;
use App\Components\MealPlanner\Application\Service\Breakdown\BreakdownServiceInterface;
use App\Components\MealPlanner\Data\Entity\MealPlanBreakdownEntity;
use Illuminate\Pagination\LengthAwarePaginator;

class BreakdownService implements BreakdownServiceInterface
{
    public function __construct(
        private readonly BreakdownRepositoryInterface $breakdownRepository,
        private readonly BreakdownQueryInterface $breakdownQuery
    )
    {
    }

    public function store(array $data): void
    {
        $this->breakdownRepository->create($data);
    }

    public function update(string $uuid, array $data): void
    {
        $this->breakdownRepository->update($uuid, $data);
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
