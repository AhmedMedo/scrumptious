<?php

namespace App\Components\MealPlanner\Application\Repository\Breakdown;

use App\Components\MealPlanner\Data\Entity\MealPlanBreakdownEntity;

interface BreakdownRepositoryInterface
{
    public function create(array $data): MealPlanBreakdownEntity;

    public function update(string $uuid, array $data): MealPlanBreakdownEntity;

    public function delete(string $uuid): void;
}

