<?php

namespace App\Components\Auth\Application\Mapper;

use App\Components\Content\Data\Entity\CategoryEntity;
use App\Components\Content\Presentation\ViewModel\CategoryViewModel;

class CategoryViewModelMapper
{

    public function fromEntity(CategoryEntity $categoryEntity)
    {
        return new CategoryViewModel(
            uuid: $categoryEntity->getKey(),
            name: $categoryEntity->name,
            image: $categoryEntity->getFirstMediaUrl('image'),
            recipesCount: $categoryEntity->recipes_count
        );
    }

}
