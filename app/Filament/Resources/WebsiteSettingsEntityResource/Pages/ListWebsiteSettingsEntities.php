<?php

namespace App\Filament\Resources\WebsiteSettingsEntityResource\Pages;

use App\Filament\Resources\WebsiteSettingsEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteSettingsEntities extends ListRecords
{
    protected static string $resource = WebsiteSettingsEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Add Setting'),
        ];
    }
}
