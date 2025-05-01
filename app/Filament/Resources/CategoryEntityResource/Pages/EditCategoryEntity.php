<?php

namespace App\Filament\Resources\CategoryEntityResource\Pages;

use App\Filament\Resources\CategoryEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryEntity extends EditRecord
{
    protected static string $resource = CategoryEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
