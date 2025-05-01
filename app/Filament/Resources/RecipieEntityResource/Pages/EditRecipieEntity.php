<?php

namespace App\Filament\Resources\RecipieEntityResource\Pages;

use App\Filament\Resources\RecipieEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecipieEntity extends EditRecord
{
    protected static string $resource = RecipieEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
