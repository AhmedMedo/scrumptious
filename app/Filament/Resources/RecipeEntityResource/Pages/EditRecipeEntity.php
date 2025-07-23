<?php

namespace App\Filament\Resources\RecipeEntityResource\Pages;

use App\Components\Recipe\Data\Entity\IngredientEntity;
use App\Filament\Resources\RecipeEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditRecipeEntity extends EditRecord
{
    protected static string $resource = RecipeEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $instructions = $this->data['instructions'] ?? [];
        $ingredients = $this->data['ingredients'] ?? [];
        $imageState = $this->data['image'] ?? [];
        $videoState = $this->data['video'] ?? [];

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

        if (empty($imageState)) {
            $this->record->clearMediaCollection('image');
        }
        if (empty($videoState)) {
            $this->record->clearMediaCollection('video');
        }
    }

}
