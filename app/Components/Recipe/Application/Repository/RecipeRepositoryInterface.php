<?php

namespace App\Components\Recipe\Application\Repository;

use App\Components\Recipe\Data\Entity\RecipeEntity;

interface RecipeRepositoryInterface
{
    public function create(array $data): RecipeEntity;

    public function update(string $uuid, array $data): void;

    public function delete(string $uuid): void;

}
