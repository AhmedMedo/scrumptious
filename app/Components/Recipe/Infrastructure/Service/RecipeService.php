<?php

namespace App\Components\Recipe\Infrastructure\Service;

use App\Components\Recipe\Application\Query\RecipeQueryInterface;
use App\Components\Recipe\Application\Repository\RecipeRepositoryInterface;
use App\Components\Recipe\Application\Service\RecipeServiceInterface;

class RecipeService implements RecipeServiceInterface
{
    public function __construct(
        private readonly RecipeRepositoryInterface $recipeRepository,
        private readonly RecipeQueryInterface $recipeQuery,
    )
    {
    }

    public function all()
    {
        // TODO: Implement collection() method.
    }

    public function store(array $data)
    {
        // TODO: Implement store() method.
    }

    public function update(string $uuid, array $data)
    {
        // TODO: Implement update() method.
    }

    public function delete(string $uuid)
    {
        // TODO: Implement delete() method.
    }

    public function findByUuid(string $uuid)
    {
        // TODO: Implement findByUuid() method.
    }
}
