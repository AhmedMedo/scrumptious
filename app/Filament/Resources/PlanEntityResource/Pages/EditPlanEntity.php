<?php

namespace App\Filament\Resources\PlanEntityResource\Pages;

use App\Filament\Resources\PlanEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanEntity extends EditRecord
{
    protected static string $resource = PlanEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
