<?php

namespace App\Filament\Resources\WebsiteSettingsEntityResource\Pages;

use App\Components\Content\Application\Service\WebsiteSettingsServiceInterface;
use App\Filament\Resources\WebsiteSettingsEntityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWebsiteSettingsEntity extends CreateRecord
{
    protected static string $resource = WebsiteSettingsEntityResource::class;

    protected function afterCreate(): void
    {
        app(WebsiteSettingsServiceInterface::class)->generatePDF($this->record);
    }
}
