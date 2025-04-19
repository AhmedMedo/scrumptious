<?php

namespace App\Components\Recipe\Application\Query;

use App\Components\Recipe\Data\Entity\IngredientEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface IngredientQueryInterface
{
    public function findByUuid(string $uuid): ?IngredientEntity;

    public function all(): Collection;

    public function paginated(): LengthAwarePaginator;

}
