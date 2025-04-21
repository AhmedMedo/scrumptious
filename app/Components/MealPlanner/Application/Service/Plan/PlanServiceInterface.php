<?php

namespace App\Components\MealPlanner\Application\Service\Plan;

use App\Components\MealPlanner\Data\Entity\PlanEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface PlanServiceInterface
{
    public function paginate(string $userUuid): LengthAwarePaginator;

    public function show(string $uuid): ?PlanEntity;
    public function store(array $data): void;

    public function update(string $uuid, array $data): void;

    public function delete(string $uuid): void;
}
