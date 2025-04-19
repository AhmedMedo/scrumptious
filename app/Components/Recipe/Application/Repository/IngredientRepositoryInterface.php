<?php

namespace App\Components\Recipe\Application\Repository;

use App\Components\Recipe\Data\Entity\IngredientEntity;

interface IngredientRepositoryInterface
{
    public function create(array $data): IngredientEntity;

    public function update(string $uuid, array $data): void;

    public function delete(string $uuid): void;
}
