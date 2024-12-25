<?php

namespace App\Components\Content\Infrastructure\Repository;

use App\Components\Content\Application\Repository\CountryRepositoryInterface;
use App\Components\Content\Data\Entity\CountryEntity;
use Illuminate\Support\Collection;

class CountryRepository implements CountryRepositoryInterface
{
    public function findAll(): Collection
    {
        return CountryEntity::query()
        ->when(request()->has('has_cards'), function ($query) {
            $query->whereHas('giftCards',fn($query) => $query->where('is_active','=', true))->union(
                CountryEntity::query()->where('is_global', true)
            );
        })->where('is_global', false)
        ->orderBy('name->' . app()->getLocale())
            ->get()
            ->sortBy(fn($country) => !$country->is_global);
    }

    public function findOnlyForGiftCards(): Collection
    {
        return CountryEntity::query()
            ->whereHas('giftCards',function ($query) {
                $query->where('is_active', '=', true);
            })
//            ->where('is_active', '=', true)
            ->orderBy('name->' . app()->getLocale())
            ->get()
            ->sortBy(fn($country) => !$country->is_global);
    }
}
