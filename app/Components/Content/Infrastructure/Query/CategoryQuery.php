<?php

namespace App\Components\Content\Infrastructure\Query;

use App\Components\Auth\Application\Mapper\CategoryViewModelMapper;
use App\Components\Content\Application\Query\CategoryQueryInterface;
use App\Components\Content\Data\Entity\CategoryEntity;
use Illuminate\Support\Collection;

class CategoryQuery implements CategoryQueryInterface
{


    public function __construct(
        private readonly CategoryViewModelMapper $categoryViewModelMapper,
    )
    {
    }

    public function all(): Collection
    {
        return CategoryEntity::query()->where('is_active','=', true)
            ->get()
            ->map(fn (CategoryEntity $category) => $this->categoryViewModelMapper->fromEntity($category));
    }

}
