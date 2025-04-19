<?php

namespace App\Components\Recipe\Application\Query;

use App\Components\Recipe\Data\Entity\RecipeEntity;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RecipeQueryInterface
{

    public function findByUuid(string $uuid): ?RecipeEntity;

    public function all(): Collection;

    public function paginated(string $userUuid): LengthAwarePaginator;

}
