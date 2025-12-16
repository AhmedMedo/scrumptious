<?php

namespace App\Components\MealPlanner\Application\Service\Breakdown;

use App\Components\MealPlanner\Data\Entity\MealPlanBreakdownEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface BreakdownServiceInterface
{
    public function store(array $data, string $userUuid): MealPlanBreakdownEntity;

    public function update(string $uuid, array $data): MealPlanBreakdownEntity;

    public function delete(string $uuid): void;

    public function show(string $uuid): ?MealPlanBreakdownEntity;

    public function paginate(?string $planUuid = null, ?string $date = null): LengthAwarePaginator;
}

