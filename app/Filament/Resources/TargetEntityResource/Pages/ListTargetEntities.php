<?php

namespace App\Filament\Resources\TargetEntityResource\Pages;

use App\Filament\Resources\TargetEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTargetEntities extends ListRecords
{
    protected static string $resource = TargetEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
