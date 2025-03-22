<?php

namespace App\Components\Recipe\Presentation\ViewModel;

use phpseclib3\Math\PrimeField\Integer;

class RecipeViewModel
{

    public function __construct(
        public readonly string $uuid,
        public readonly string $title,
        public readonly ?integer $cookingMinutes = null,
        public readonly ?integer $totalCarbs = null,
        public readonly ?integer $totalProteins = null,
        public readonly ?integer $totalFats = null,
        public readonly ?Integer $totalCalories = null,
        public readonly ?string $youTubeVideo = null,
        public readonly ?string $image = null,
        public readonly ?string $description = null,
        public readonly ?array $instructions = [],
        public readonly ?array $ingredients = [],
        public readonly ?array $categories = [],

    )
    {
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'cooking_minutes' => $this->cookingMinutes,
            'total_carbs' => $this->totalCarbs,
            'total_proteins' => $this->totalProteins,
            'total_fats' => $this->totalFats,
            'total_calories' => $this->totalCalories,
            'youtube_video' => $this->youTubeVideo,
            'image' => $this->image,
            'description' => $this->description,
            'instructions' => $this->instructions,
            'ingredients' => $this->ingredients,
            'categories' => $this->categories
        ];
    }

}
