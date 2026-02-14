<?php

namespace App\Filament\Resources\AdminBroadcastEntityResource\Pages;

use App\Filament\Resources\AdminBroadcastEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdminBroadcastEntities extends ListRecords
{
    protected static string $resource = AdminBroadcastEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
