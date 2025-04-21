<?php

namespace App\Components\MealPlanner\Application\Repository\Plan;

interface PlanRepositoryInterface
{
    public function create(array $data): void;

    public function update(string $uuid, array $data):void;

    public function delete(string $uuid): void;
}
