<?php

namespace App\Components\Recipe\Infrastructure\Query;

use App\Components\Recipe\Application\Query\RecipeQueryInterface;
use Illuminate\Support\Collection;

class RecipeQuery implements RecipeQueryInterface
{

    public function findByUuid(string $uuid)
    {
        // TODO: Implement findByUuid() method.
    }

    public function all(): Collection
    {

    }
}
