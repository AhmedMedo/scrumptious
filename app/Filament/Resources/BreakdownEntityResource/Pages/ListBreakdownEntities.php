<?php

namespace App\Filament\Resources\BreakdownEntityResource\Pages;

use App\Filament\Resources\BreakdownEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBreakdownEntities extends ListRecords
{
    protected static string $resource = BreakdownEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
