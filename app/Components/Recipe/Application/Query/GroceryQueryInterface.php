<?php

namespace App\Components\Recipe\Application\Query;

use App\Components\Recipe\Data\Entity\GroceryEntity;
use App\Components\Recipe\Data\Entity\IngredientEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface GroceryQueryInterface
{
    public function findByUuid(string $uuid): ?GroceryEntity;

    public function all(): Collection;

    public function paginated(): LengthAwarePaginator;

}
