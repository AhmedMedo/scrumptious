<?php

namespace App\Filament\Resources\TargetEntityResource\Pages;

use App\Filament\Resources\TargetEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTargetEntity extends EditRecord
{
    protected static string $resource = TargetEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
