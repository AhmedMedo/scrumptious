<?php

namespace App\Filament\Resources\BreakdownEntityResource\Pages;

use App\Filament\Resources\BreakdownEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBreakdownEntity extends EditRecord
{
    protected static string $resource = BreakdownEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Update meals to include plan_uuid
        $breakdown = $this->record;
        if ($breakdown && $breakdown->plan_uuid) {
            $breakdown->meals()->update(['plan_uuid' => $breakdown->plan_uuid]);
        }
    }
}
