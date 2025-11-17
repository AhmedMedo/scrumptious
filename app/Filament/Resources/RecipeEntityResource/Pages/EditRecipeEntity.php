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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load instructions in the correct order
        if ($this->record && $this->record->exists) {
            $data['instructions'] = $this->record->instructions()
                ->orderBy('order')
                ->get()
                ->map(function ($instruction) {
                    return ['content' => $instruction->content];
                })
                ->toArray();
                
            // Load ingredients in the correct order
            $data['ingredients'] = $this->record->ingredients()
                ->orderBy('recipe_ingredient.order')
                ->get()
                ->map(function ($ingredient) {
                    return ['content' => $ingredient->content];
                })
                ->toArray();
        }
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure instructions is always an array
        if (!isset($data['instructions']) || !is_array($data['instructions'])) {
            $data['instructions'] = [];
        }
        
        // Ensure ingredients is always an array
        if (!isset($data['ingredients']) || !is_array($data['ingredients'])) {
            $data['ingredients'] = [];
        }
        
        return $data;
    }

    protected function afterFill(): void
    {
        // Ensure instructions is always an array after filling the form
        if (!isset($this->data['instructions']) || !is_array($this->data['instructions'])) {
            $this->data['instructions'] = [];
        }
        
        // Ensure ingredients is always an array after filling the form
        if (!isset($this->data['ingredients']) || !is_array($this->data['ingredients'])) {
            $this->data['ingredients'] = [];
        }
    }

    protected function afterSave(): void
    {
        $instructions = $this->data['instructions'] ?? [];
        $ingredients = $this->data['ingredients'] ?? [];
        $imageState = $this->data['image'] ?? [];
        $videoState = $this->data['video'] ?? [];

        // Ensure instructions is always an array
        if (!is_array($instructions)) {
            $instructions = [];
        }

        // Save instructions (HasMany)
        $this->record->instructions()->delete();
        
        // Reset array keys to ensure we have sequential integer indexes
        $instructions = array_values($instructions);
        
        foreach ($instructions as $index => $instructionData) {
            if (is_array($instructionData) && isset($instructionData['content'])) {
                $this->record->instructions()->create([
                    'content' => $instructionData['content'],
                    'order' => $index,
                ]);
            }
        }

        // Ensure ingredients is always an array
        if (!is_array($ingredients)) {
            $ingredients = [];
        }

        // Save ingredients (BelongsToMany with pivot)
        $this->record->ingredients()->detach();
        
        // Reset array keys to ensure we have sequential integer indexes
        $ingredients = array_values($ingredients);
        
        foreach ($ingredients as $index => $ingredientData) {
            if (is_array($ingredientData) && isset($ingredientData['content'])) {
                $ingredientName = $ingredientData['content'];

                $ingredient = IngredientEntity::firstOrCreate(
                    ['content' => $ingredientName],
                    ['uuid' => Str::uuid()]
                );
                
                // Attach with order in pivot table
                $this->record->ingredients()->attach($ingredient, ['order' => $index]);
            }
        }

        if (empty($imageState)) {
            $this->record->clearMediaCollection('image');
        }
        if (empty($videoState)) {
            $this->record->clearMediaCollection('video');
        }
    }

}
