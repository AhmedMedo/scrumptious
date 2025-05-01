<?php

namespace App\Filament\Resources\RecipeEntityResource\Pages;

use App\Filament\Resources\RecipeEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecipeEntities extends ListRecords
{
    protected static string $resource = RecipeEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
