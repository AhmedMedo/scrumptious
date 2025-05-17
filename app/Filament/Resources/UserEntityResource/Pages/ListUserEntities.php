<?php

namespace App\Filament\Resources\UserEntityResource\Pages;

use App\Filament\Resources\UserEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserEntities extends ListRecords
{
    protected static string $resource = UserEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add New User'),
        ];
    }

}
