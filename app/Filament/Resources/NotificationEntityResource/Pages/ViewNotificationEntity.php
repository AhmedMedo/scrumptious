<?php

namespace App\Filament\Resources\NotificationEntityResource\Pages;

use App\Filament\Resources\NotificationEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNotificationEntity extends ViewRecord
{
    protected static string $resource = NotificationEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
