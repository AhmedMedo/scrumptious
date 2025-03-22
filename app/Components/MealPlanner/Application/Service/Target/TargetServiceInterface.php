<?php

namespace App\Components\MealPlanner\Application\Service\Target;

use App\Components\MealPlanner\Data\Entity\TargetEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface TargetServiceInterface
{
    public function paginated(string $userUuid): LengthAwarePaginator;

    public function all();

    public function store(array $data): TargetEntity;

    public function update(string $uuid, array $data);

    public function delete(string $uuid);

    public function findByUuid(string $uuid);
}
