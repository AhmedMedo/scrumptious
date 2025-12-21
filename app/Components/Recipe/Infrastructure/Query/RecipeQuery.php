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

    public function paginated(?string $userUuid = null, bool $withAdminRecipes = false): LengthAwarePaginator
    {
        return RecipeEntity::filter(request()->all())
            ->when(request('is_admin'), function ($query) {
                $query->whereNotNull('admin_uuid');
            })
            ->when($userUuid, function ($query) use ($userUuid, $withAdminRecipes) {
                $query->where(function ($q) use ($userUuid, $withAdminRecipes) {
                    $q->where('user_uuid', $userUuid);
                    if ($withAdminRecipes) {
                        $q->orWhereNotNull('admin_uuid');
                    }
                });
            })
            ->with([
                'categories',
                'ingredients',
                'instructions'
            ])
            ->where('is_active', '=', true)
            ->latest()
            ->paginate(
                request()->get('per_page', 10),
                ['*'],
            );
    }
}
