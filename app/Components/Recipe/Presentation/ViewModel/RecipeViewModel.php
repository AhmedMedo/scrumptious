<?php

namespace App\Components\Recipe\Presentation\ViewModel;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RecipeViewModel',
    properties: [
        new OA\Property(property: 'uuid', type: 'string'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'cooking_minutes', type: 'integer'),
        new OA\Property(property: 'total_carbs', type: 'integer'),
        new OA\Property(property: 'total_proteins', type: 'integer'),
        new OA\Property(property: 'total_fats', type: 'integer'),
        new OA\Property(property: 'total_calories', type: 'integer'),
        new OA\Property(property: 'youtube_video', type: 'string'),
        new OA\Property(property: 'image', type: 'string'),
        new OA\Property(property: 'description', type: 'string'),
        new OA\Property(
            property: 'instructions',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'uuid', type: 'string'),
                    new OA\Property(property: 'content', type: 'string'),
                ],
                type: 'object'
            )
        ),
        new OA\Property(
            property: 'ingredients',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'uuid', type: 'string'),
                    new OA\Property(property: 'content', type: 'string'),
                ],
                type: 'object'
            )
        ),
        new OA\Property(
            property: 'categories',
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(property: 'uuid', type: 'string'),
                    new OA\Property(property: 'name', type: 'string'),
                ],
                type: 'object'
            )
        ),
        new OA\Property(property: 'is_favorite', type: 'boolean'),
    ],
    type: 'object'
)]
class RecipeViewModel
{

    public function __construct(
        public readonly string   $uuid,
        public readonly string   $title,
        public readonly ?int $cookingMinutes = null,
        public readonly ?int $totalCarbs = null,
        public readonly ?int $totalProteins = null,
        public readonly ?int $totalFats = null,
        public readonly ?int $totalCalories = null,
        public readonly ?string  $youTubeVideo = null,
        public readonly ?string  $image = null,
        public readonly ?string  $description = null,
        public readonly ?array   $instructions = [],
        public readonly ?array   $ingredients = [],
        public readonly ?array   $categories = [],
        public readonly ?bool    $isFavorite = null,
        public readonly ?string $video = null,

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
            'categories' => $this->categories,
            'is_favorite' => $this->isFavorite,
            'video' => $this->video,
        ];
    }

}
