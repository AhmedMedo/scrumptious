<?php

namespace App\Components\Recipe\Infrastructure\Repository;

use App\Components\Recipe\Application\Query\RecipeQueryInterface;
use App\Components\Recipe\Application\Repository\RecipeRepositoryInterface;
use App\Components\Recipe\Data\Entity\RecipeEntity;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class RecipeRepository implements RecipeRepositoryInterface
{


    public function __construct(
        private readonly RecipeQueryInterface $recipeQuery
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

            foreach ($data['instructions'] as $instruction) {
                $recipe->instructions()->create([
                    'content' => Arr::get($instruction, 'content'),
                ]);
            }
        }


        if (Arr::has($data, 'ingredients')) {
            foreach ($data['ingredients'] as $ingredient) {
                $recipe->ingredients()->create([
                    'content' => Arr::get($ingredient, 'content'),
                ]);
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
            foreach ($data['instructions'] as $instruction) {
                $recipe->instructions()->create([
                    'content' => Arr::get($instruction, 'content'),
                ]);
            }
        }


        if (Arr::has($data, 'ingredients')) {
            $recipe->ingredients()->delete();
            foreach ($data['ingredients'] as $ingredient) {
                $recipe->ingredients()->create([
                    'content' => Arr::get($ingredient, 'content'),
                ]);
            }
        }

    }

    public function delete(string $uuid): void
    {
        $recipe = $this->recipeQuery->findByUuid($uuid);
        $recipe->clearMediaCollection('image');
        $recipe->instructions()->delete();
        $recipe->ingredients()->delete();
        $recipe->delete();
    }
}
