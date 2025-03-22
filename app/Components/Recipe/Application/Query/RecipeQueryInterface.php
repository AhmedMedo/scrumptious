<?php

namespace App\Components\Recipe\Application\Query;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RecipeQueryInterface
{

    public function findByUuid(string $uuid);

    public function all(): Collection;

    public function paginated(): LengthAwarePaginator;

}
