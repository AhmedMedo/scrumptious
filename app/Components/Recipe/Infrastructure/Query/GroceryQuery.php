<?php

namespace App\Components\Recipe\Infrastructure\Query;

use App\Components\Recipe\Application\Query\GroceryQueryInterface;
use App\Components\Recipe\Data\Entity\GroceryEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GroceryQuery implements GroceryQueryInterface
{

    public function findByUuid(string $uuid): ?GroceryEntity
    {
        return GroceryEntity::query()->where('uuid', $uuid)->first();
    }

    public function all(): Collection
    {
        return GroceryEntity::filter(request()->all())->get();
    }

    public function paginated(): LengthAwarePaginator
    {
        return GroceryEntity::filter(request()->all())
            ->latest()
            ->paginate(
                request()->get('per_page', 10),
                ['*'],
            );
    }
}
