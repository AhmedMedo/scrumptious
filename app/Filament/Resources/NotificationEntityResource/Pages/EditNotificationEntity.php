<?php

namespace App\Filament\Resources\NotificationEntityResource\Pages;

use App\Filament\Resources\NotificationEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNotificationEntity extends EditRecord
{
    protected static string $resource = NotificationEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
