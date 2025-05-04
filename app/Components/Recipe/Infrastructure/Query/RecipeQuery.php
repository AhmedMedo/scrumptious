<?php

namespace App\Components\Recipe\Infrastructure\Query;

use App\Components\Recipe\Application\Query\RecipeQueryInterface;
use App\Components\Recipe\Data\Entity\RecipeEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class RecipeQuery implements RecipeQueryInterface
{

    public function findByUuid(string $uuid): ?RecipeEntity
    {
        return RecipeEntity::query()->findOrFail($uuid);
    }

    public function all(): Collection
    {
        return RecipeEntity::filter(request()->all())->get();
    }

    public function paginated(string $userUuid): LengthAwarePaginator
    {
       return RecipeEntity::filter(request()->all())
//           ->where('user_uuid', '=', $userUuid)
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
