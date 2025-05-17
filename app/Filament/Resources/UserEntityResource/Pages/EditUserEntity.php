<?php

namespace App\Filament\Resources\UserEntityResource\Pages;

use App\Filament\Resources\UserEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserEntity extends EditRecord
{
    protected static string $resource = UserEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
