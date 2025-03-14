<?php

namespace App\Components\Content\Application\Query;

use Illuminate\Support\Collection;

interface CategoryQueryInterface
{
    public function all(): Collection;
}
