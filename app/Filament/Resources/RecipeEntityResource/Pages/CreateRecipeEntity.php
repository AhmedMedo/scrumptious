<?php

namespace App\Filament\Resources\RecipeEntityResource\Pages;

use App\Components\Recipe\Data\Entity\IngredientEntity;
use App\Filament\Resources\RecipeEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CreateRecipeEntity extends CreateRecord
{
    protected static string $resource = RecipeEntityResource::class;

    protected function afterCreate(): void
    {
        $instructions = $this->data['instructions'] ?? [];
        $ingredients = $this->data['ingredients'] ?? [];
        $imageArray = $this->data['image'] ?? [];
        $videoArray = $this->data['video'] ?? [];

        // Save instructions (HasMany)
        $this->record->instructions()->delete();
        foreach ($instructions as $instructionData) {
            $this->record->instructions()->create([
                'content' => $instructionData['content'],
            ]);
        }

        // Save ingredients (BelongsToMany with pivot)
        $this->record->ingredients()->detach();
        foreach ($ingredients as $ingredientData) {
            $ingredientName = $ingredientData['content'];

            $ingredient = IngredientEntity::firstOrCreate(
                ['content' => $ingredientName],
                ['uuid' => Str::uuid()]
            );

            $this->record->ingredients()->attach($ingredient);
        }

        if (is_array($imageArray) && count($imageArray) > 0) {
            $relativePath = reset($imageArray);
            $fullPath = storage_path("app/public/{$relativePath}");

            if (file_exists($fullPath)) {
                $this->record->clearMediaCollection('image');

                $this->record
                    ->addMedia($fullPath)
                    ->usingFileName(basename($relativePath))
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            }
        }

        if (is_array($videoArray) && count($videoArray) > 0) {
            $relativePath = reset($videoArray);
            $fullPath = storage_path("app/public/{$relativePath}");

            if (file_exists($fullPath)) {
                $this->record->clearMediaCollection('video');

                $this->record
                    ->addMedia($fullPath)
                    ->usingFileName(basename($relativePath))
                    ->preservingOriginal()
                    ->toMediaCollection('video');
            }
        }

    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->guard('admin')->check()) {
            $data['admin_uuid'] = auth()->guard('admin')->user()->uuid;
        }

        return $data;
    }
}
