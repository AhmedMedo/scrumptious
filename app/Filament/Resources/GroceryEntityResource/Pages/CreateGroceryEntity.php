<?php

namespace App\Filament\Resources\GroceryEntityResource\Pages;

use App\Filament\Resources\GroceryEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGroceryEntity extends CreateRecord
{
    protected static string $resource = GroceryEntityResource::class;

    protected function afterCreate(): void
    {
        $imageArray = $this->data['image'] ?? [];

        if (is_array($imageArray) && count($imageArray) > 0) {
            $relativePath = reset($imageArray);
            $fullPath = storage_path("app/public/{$relativePath}");

            if (file_exists($fullPath)) {
                $this->record->clearMediaCollection('image');

                $this->record
                    ->addMedia($fullPath)
                    ->usingFileName(basename($relativePath))
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            }
        }
    }
}
