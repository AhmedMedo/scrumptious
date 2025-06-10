<?php

namespace App\Components\Recipe\Application\Query;

use App\Components\Recipe\Data\Entity\GroceryCategoryEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface GroceryCategoryQueryInterface
{
    public function findByUuid(string $uuid): ?GroceryCategoryEntity;

    public function all(): Collection;

    public function paginated(): LengthAwarePaginator;
}
