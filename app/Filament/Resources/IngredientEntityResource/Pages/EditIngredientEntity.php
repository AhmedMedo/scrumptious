<?php

namespace App\Filament\Resources\IngredientEntityResource\Pages;

use App\Filament\Resources\IngredientEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIngredientEntity extends EditRecord
{
    protected static string $resource = IngredientEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
