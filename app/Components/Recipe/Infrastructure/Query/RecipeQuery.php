<?php

namespace App\Components\Recipe\Infrastructure\Query;

use App\Components\Recipe\Application\Query\RecipeQueryInterface;
use App\Components\Recipe\Data\Entity\RecipeEntity;
use Illuminate\Support\Collection;

class RecipeQuery implements RecipeQueryInterface
{

    public function findByUuid(string $uuid): \Illuminate\Database\Eloquent\Model
    {
        return RecipeEntity::getByUuid($uuid);
    }

    public function all(): Collection
    {

    }
}
