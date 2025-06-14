<?php

namespace App\Filament\Resources\WebsiteSettingsEntityResource\Pages;

use App\Components\Content\Application\Service\WebsiteSettingsServiceInterface;
use App\Filament\Resources\WebsiteSettingsEntityResource;
use App\Components\Content\Data\Entity\WebsiteSettingsEntity;
use Filament\Resources\Pages\CreateRecord;

class CreateWebsiteSettingsEntity extends CreateRecord
{
    protected static string $resource = WebsiteSettingsEntityResource::class;

    public function mount(): void
    {
        $record = WebsiteSettingsEntity::first();
        if ($record) {
            $this->redirect(WebsiteSettingsEntityResource::getUrl('edit', ['record' => $record]));
        }

        parent::mount();
    }

    protected function afterCreate(): void
    {
        app(WebsiteSettingsServiceInterface::class)->generatePDF($this->record);
    }
}
