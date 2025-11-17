<?php

namespace App\Components\Recipe\Infrastructure\Repository;

use App\Components\Recipe\Application\Query\RecipeQueryInterface;
use App\Components\Recipe\Application\Repository\IngredientRepositoryInterface;
use App\Components\Recipe\Application\Repository\RecipeRepositoryInterface;
use App\Components\Recipe\Data\Entity\RecipeEntity;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class RecipeRepository implements RecipeRepositoryInterface
{


    public function __construct(
        private readonly RecipeQueryInterface $recipeQuery,
        private readonly IngredientRepositoryInterface $ingredientRepository
    )
    {
    }

    public function create(array $data): RecipeEntity
    {
        $recipe = RecipeEntity::create([
            'user_uuid' => Arr::get($data, 'user_uuid'),
            'title' => Arr::get($data, 'title'),
            'cooking_minutes' => Arr::get($data, 'cooking_minutes'),
            'total_carbs' => Arr::get($data, 'total_carbs'),
            'total_proteins' => Arr::get($data, 'total_proteins'),
            'total_fats' => Arr::get($data, 'total_fats'),
            'total_calories' => Arr::get($data, 'total_calories'),
            'youtube_video' => Arr::get($data, 'youtube_video'),
            'description' => Arr::get($data, 'description'),
        ]);

        //Image
        if (Arr::has($data, 'image')) {
            $filePath = Arr::get($data, 'image.file_path');
            $recipe->addMedia(Storage::disk('public')->path($filePath))->toMediaCollection('image');
        }


        if (Arr::has($data, 'instructions')) {
            // Reset array keys to ensure we have sequential integer indexes
            $instructions = array_values($data['instructions']);
            
            foreach ($instructions as $index => $instruction) {
                $recipe->instructions()->create([
                    'content' => Arr::get($instruction, 'content'),
                    'order' => $index,
                ]);
            }
        }


        if (Arr::has($data, 'ingredients')) {
            // Reset array keys to ensure we have sequential integer indexes
            $ingredients = array_values($data['ingredients']);
            
            foreach ($ingredients as $index => $ingredient) {
                $ingredientEntity = $this->ingredientRepository->create([
                    'content' => Arr::get($ingredient, 'content'),
                ]);
                // Attach with order in pivot table
                $recipe->ingredients()->attach($ingredientEntity, ['order' => $index]);
            }
        }

        return $recipe;
    }

    public function update(string $uuid, array $data): void
    {
        $recipe = $this->recipeQuery->findByUuid($uuid);
        $recipe->update([
            'title' => Arr::get($data, 'title'),
            'cooking_minutes' => Arr::get($data, 'cooking_minutes'),
            'total_carbs' => Arr::get($data, 'total_carbs'),
            'total_proteins' => Arr::get($data, 'total_proteins'),
            'total_fats' => Arr::get($data, 'total_fats'),
            'total_calories' => Arr::get($data, 'total_calories'),
            'youtube_video' => Arr::get($data, 'youtube_video'),
            'description' => Arr::get($data, 'description'),
        ]);

        if (Arr::has($data, 'image')) {
            $recipe->clearMediaCollection('image');
            $filePath = Arr::get($data, 'image.file_path');
            $recipe->addMedia(Storage::disk('public')->path($filePath))->toMediaCollection('image');
        }


        if (Arr::has($data, 'instructions')) {
            $recipe->instructions()->delete();
            
            // Reset array keys to ensure we have sequential integer indexes
            $instructions = array_values($data['instructions']);
            
            foreach ($instructions as $index => $instruction) {
                $recipe->instructions()->create([
                    'content' => Arr::get($instruction, 'content'),
                    'order' => $index,
                ]);
            }
        }


        if (Arr::has($data, 'ingredients')) {
            $recipe->ingredients()->detach();
            
            // Reset array keys to ensure we have sequential integer indexes
            $ingredients = array_values($data['ingredients']);
            
            foreach ($ingredients as $index => $ingredient) {
                $ingredientEntity = $this->ingredientRepository->create([
                    'content' => Arr::get($ingredient, 'content'),
                ]);
                // Attach with order in pivot table
                $recipe->ingredients()->attach($ingredientEntity, ['order' => $index]);
            }
        }

    }

    public function delete(string $uuid): void
    {
        $recipe = $this->recipeQuery->findByUuid($uuid);
        $recipe->clearMediaCollection('image');
        $recipe->instructions()->delete();
        $recipe->ingredients()->detach();
        $recipe->delete();
    }
}
