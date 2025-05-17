<?php

namespace App\Filament\Resources\CategoryEntityResource\Pages;

use App\Filament\Resources\CategoryEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryEntities extends ListRecords
{
    protected static string $resource = CategoryEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add New Category'),
        ];
    }
}
