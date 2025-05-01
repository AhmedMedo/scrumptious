<?php

namespace App\Filament\Resources\RecipeEntityResource\Pages;

use App\Filament\Resources\RecipeEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecipeEntity extends EditRecord
{
    protected static string $resource = RecipeEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
