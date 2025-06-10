<?php

namespace App\Filament\Resources\GroceryCategoryEntityResource\Pages;

use App\Filament\Resources\GroceryCategoryEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGroceryCategoryEntity extends EditRecord
{
    protected static string $resource = GroceryCategoryEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
