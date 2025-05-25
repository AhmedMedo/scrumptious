<?php

namespace App\Filament\Resources\GroceryEntityResource\Pages;

use App\Filament\Resources\GroceryEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGroceryEntities extends ListRecords
{
    protected static string $resource = GroceryEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
