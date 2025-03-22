<?php

namespace App\Components\MealPlanner\Infrastructure\Query\Target;

use App\Components\MealPlanner\Application\Query\Target\TargetQueryInterface;
use App\Components\MealPlanner\Data\Entity\TargetEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TargetQuery implements TargetQueryInterface
{

    public function findByUuid(string $uuid)
    {
        return TargetEntity::query()->where('uuid', $uuid)->first();
    }

    public function all(): Collection
    {
        return TargetEntity::query()->get();
    }

    public function paginated(string $userUuid): LengthAwarePaginator
    {
        return TargetEntity::query()
            ->where('user_uuid', '=', $userUuid)
            ->paginate(
            request()->get('per_page', 10),
            ['*'],
        );
    }
}
