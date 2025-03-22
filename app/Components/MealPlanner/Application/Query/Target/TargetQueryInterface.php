<?php

namespace App\Components\MealPlanner\Application\Query\Target;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TargetQueryInterface
{

    public function findByUuid(string $uuid);

    public function all(): Collection;

    public function paginated(string $userUuid): LengthAwarePaginator;

}
