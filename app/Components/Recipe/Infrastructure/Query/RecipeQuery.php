<?php

namespace App\Components\Recipe\Infrastructure\Query;

use App\Components\Recipe\Application\Query\RecipeQueryInterface;
use App\Components\Recipe\Data\Entity\RecipeEntity;
use Illuminate\Pagination\LengthAwarePaginator;
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

    public function paginated(): LengthAwarePaginator
    {
       return RecipeEntity::query()
            ->with([
                'categories',
                'ingredients',
                'instructions'
            ])->where('is_active','=', true)
           ->latest()
           ->paginate(
               request()->get('per_page', 10),
               ['*'],
           );
    }
}
