<?php

namespace App\Filament\Resources\RecipieEntityResource\Pages;

use App\Filament\Resources\RecipieEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecipieEntities extends ListRecords
{
    protected static string $resource = RecipieEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
