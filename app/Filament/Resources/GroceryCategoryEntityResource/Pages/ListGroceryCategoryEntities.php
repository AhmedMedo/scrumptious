<?php

namespace App\Filament\Resources\GroceryCategoryEntityResource\Pages;

use App\Filament\Resources\GroceryCategoryEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGroceryCategoryEntities extends ListRecords
{
    protected static string $resource = GroceryCategoryEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add New Grocery Category'),
        ];
    }
}
