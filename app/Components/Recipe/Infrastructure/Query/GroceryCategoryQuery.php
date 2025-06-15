<?php

namespace App\Components\Recipe\Infrastructure\Query;

use App\Components\Recipe\Application\Query\GroceryCategoryQueryInterface;
use App\Components\Recipe\Data\Entity\GroceryCategoryEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GroceryCategoryQuery implements GroceryCategoryQueryInterface
{
    public function findByUuid(string $uuid): ?GroceryCategoryEntity
    {
        return GroceryCategoryEntity::query()->where('uuid', $uuid)->first();
    }

    public function all(): Collection
    {
        return GroceryCategoryEntity::filter(request()->all())->get();
    }

    public function paginated(): LengthAwarePaginator
    {
        return GroceryCategoryEntity::filter(request()->all())
            ->with('groceries',fn($q) => $q->where('is_active', true))
            ->latest()
            ->paginate(
                request()->get('per_page', 10),
                ['*'],
            );
    }
}
