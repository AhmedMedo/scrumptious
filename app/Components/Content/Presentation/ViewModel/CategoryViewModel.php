<?php

namespace App\Components\Content\Presentation\ViewModel;
class CategoryViewModel
{


    public function __construct(
        public readonly string $uuid,
        public readonly string $name,
        public readonly ?string $image = null,
        public readonly ?int $recipesCount = null
    )
    {
    }


    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'image' => $this->image,
            'recipes_count' => $this->recipesCount
        ];
    }
}
