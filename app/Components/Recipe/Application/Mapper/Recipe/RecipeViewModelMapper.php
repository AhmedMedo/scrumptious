<?php

namespace App\Components\Recipe\Application\Mapper\Recipe;

use App\Components\Recipe\Data\Entity\RecipeEntity;
use App\Components\Recipe\Data\Entity\RecipeIngredientEntity;
use App\Components\Recipe\InstructionEntity;
use App\Components\Recipe\Presentation\ViewModel\RecipeViewModel;
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
    ],
    type: 'object'
)]

class RecipeViewModelMapper
{
    public function fromEntity(RecipeEntity $recipeEntity): RecipeViewModel
    {
        return new RecipeViewModel(
            uuid: $recipeEntity->getKey(),
            title: $recipeEntity->title,
            cookingMinutes: $recipeEntity->cooking_minutes,
            totalCarbs: $recipeEntity->total_carbs,
            totalProteins: $recipeEntity->total_proteins,
            totalFats: $recipeEntity->total_fats,
            totalCalories: $recipeEntity->total_calories,
            youTubeVideo: $recipeEntity->youtube_video,
            image: $recipeEntity?->getFirstMediaUrl('image'),
            description: $recipeEntity->description,
            instructions: $recipeEntity?->instructions->map(fn(InstructionEntity $instruction) => [
                'uuid' => $instruction->getKey(),
                'content' => $instruction->content
            ])?->toArray(),
            ingredients: $recipeEntity?->ingredients?->map(fn(RecipeIngredientEntity $ingredient) => [
                'uuid' => $ingredient->getKey(),
                'content' => $ingredient->content
            ])->toArray(),
            categories: $recipeEntity?->categories?->toArray()
        );
    }
}
