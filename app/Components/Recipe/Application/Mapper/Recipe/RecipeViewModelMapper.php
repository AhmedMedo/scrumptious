<?php

namespace App\Components\Recipe\Application\Mapper\Recipe;

use App\Components\Recipe\Data\Entity\IngredientEntity;
use App\Components\Recipe\Data\Entity\InstructionEntity;
use App\Components\Recipe\Data\Entity\RecipeEntity;
use App\Components\Recipe\Presentation\ViewModel\RecipeViewModel;



class RecipeViewModelMapper
{
    public function fromEntity(RecipeEntity $recipeEntity): RecipeViewModel
    {
        $user = auth()->guard('api')->user();
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
                'content' => $instruction->content,
                'image' => $instruction->getFirstMediaUrl('image'),
            ])?->toArray(),
            ingredients: $recipeEntity?->ingredients?->map(fn(IngredientEntity $ingredient) => [
                'uuid' => $ingredient->getKey(),
                'content' => $ingredient->content,
                'image' => $ingredient->getFirstMediaUrl('image'),
            ])->toArray(),
            categories: $recipeEntity?->categories?->toArray(),
            isFavorite: $user && $recipeEntity->hasBeenFavoritedBy($user),
            video: $recipeEntity?->getFirstMediaUrl('video'),
            isPopular: $recipeEntity->is_popular ?? false,
        );
    }
}
