<?php

namespace App\Components\Recipe\Application\Service;

use App\Components\Recipe\Data\Entity\RecipeEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface RecipeServiceInterface
{

    public function paginated(?string $userUuid = null): LengthAwarePaginator;

    public function all();

    public function store(array $data): RecipeEntity;

    public function update(string $uuid, array $data);

    public function delete(string $uuid);

    public function findByUuid(string $uuid);

    public function toggleFavourite(string $uuid): void;

}
