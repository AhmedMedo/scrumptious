<?php

namespace App\Filament\Resources\IngredientEntityResource\Pages;

use App\Filament\Resources\IngredientEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIngredientEntities extends ListRecords
{
    protected static string $resource = IngredientEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add New Ingredient'),
        ];
    }
}
