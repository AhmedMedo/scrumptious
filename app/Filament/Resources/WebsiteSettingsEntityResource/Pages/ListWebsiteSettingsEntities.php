<?php

namespace App\Filament\Resources\WebsiteSettingsEntityResource\Pages;

use App\Filament\Resources\WebsiteSettingsEntityResource;
use App\Components\Content\Data\Entity\WebsiteSettingsEntity;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteSettingsEntities extends ListRecords
{
    protected static string $resource = WebsiteSettingsEntityResource::class;

    protected function getHeaderActions(): array
    {
        if (WebsiteSettingsEntity::count() === 0) {
            return [
                Actions\CreateAction::make()->label('Add Setting'),
            ];
        }

        return [];
    }
}
