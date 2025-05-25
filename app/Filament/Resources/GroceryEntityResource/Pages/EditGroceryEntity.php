<?php

namespace App\Filament\Resources\GroceryEntityResource\Pages;

use App\Filament\Resources\GroceryEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGroceryEntity extends EditRecord
{
    protected static string $resource = GroceryEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
