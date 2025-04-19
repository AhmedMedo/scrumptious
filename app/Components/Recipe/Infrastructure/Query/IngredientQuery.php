<?php

namespace App\Components\Recipe\Infrastructure\Query;

use App\Components\Recipe\Application\Query\IngredientQueryInterface;
use App\Components\Recipe\Data\Entity\IngredientEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class IngredientQuery implements IngredientQueryInterface
{

    public function findByUuid(string $uuid): ?IngredientEntity
    {
        return IngredientEntity::query()->where('uuid', $uuid)->first();
    }

    public function all(): Collection
    {
       return IngredientEntity::filter(request()->all())->get();
    }

    public function paginated(): LengthAwarePaginator
    {
       return IngredientEntity::filter(request()->all())
           ->latest()
           ->paginate(
               request()->get('per_page', 10),
               ['*'],
           );
    }
}
