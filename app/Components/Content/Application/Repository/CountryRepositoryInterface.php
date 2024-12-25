<?php

namespace App\Components\Content\Application\Repository;

use Illuminate\Support\Collection;

interface CountryRepositoryInterface
{
    public function findAll(): Collection;

    public function findOnlyForGiftCards(): Collection;
}
