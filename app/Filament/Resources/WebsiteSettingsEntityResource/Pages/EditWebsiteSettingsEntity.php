<?php

namespace App\Filament\Resources\WebsiteSettingsEntityResource\Pages;

use App\Components\Content\Application\Service\WebsiteSettingsServiceInterface;
use App\Filament\Resources\WebsiteSettingsEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteSettingsEntity extends EditRecord
{
    protected static string $resource = WebsiteSettingsEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function afterSave(): void
    {
        app(WebsiteSettingsServiceInterface::class)->generatePDF($this->record);
    }
}
