<?php

namespace App\Filament\Resources\PlanEntityResource\Pages;

use App\Filament\Resources\PlanEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanEntities extends ListRecords
{
    protected static string $resource = PlanEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
