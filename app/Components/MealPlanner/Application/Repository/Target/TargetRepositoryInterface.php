<?php

namespace App\Components\MealPlanner\Application\Repository\Target;

use App\Components\MealPlanner\Data\Entity\TargetEntity;

interface TargetRepositoryInterface
{

    public function create(array $data): TargetEntity;

    public function update(string $uuid, array $data): void;

    public function delete(string $uuid): void;
}
