<?php

namespace App\Components\Recipe\Infrastructure\Service;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Recipe\Application\Query\RecipeQueryInterface;
use App\Components\Recipe\Application\Repository\RecipeRepositoryInterface;
use App\Components\Recipe\Application\Service\RecipeServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class RecipeService implements RecipeServiceInterface
{
    public function __construct(
        private readonly RecipeRepositoryInterface $recipeRepository,
        private readonly RecipeQueryInterface $recipeQuery,
        private readonly UserServiceInterface $userService
    )
    {
    }

    public function all()
    {
        // TODO: Implement collection() method.
    }

    public function store(array $data): \App\Components\Recipe\Data\Entity\RecipeEntity
    {
        return $this->recipeRepository->create($data);
    }

    public function update(string $uuid, array $data):void
    {
        $this->recipeRepository->update($uuid, $data);
    }

    public function delete(string $uuid): void
    {
        $this->recipeRepository->delete($uuid);
    }

    public function findByUuid(string $uuid): ?\App\Components\Recipe\Data\Entity\RecipeEntity
    {
        return $this->recipeQuery->findByUuid($uuid);
    }

    public function paginated(?string $userUuid = null): LengthAwarePaginator
    {
        return $this->recipeQuery->paginated($userUuid);
    }

    public function toggleFavourite(string $uuid): void
    {
        $user = $this->userService->userEntity();
        $recipe = $this->recipeQuery->findByUuid($uuid);
        $user->toggleFavorite($recipe);
    }
}
