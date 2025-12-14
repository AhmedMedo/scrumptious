<?php

namespace App\Filament\Resources\BreakdownEntityResource\Pages;

use App\Filament\Resources\BreakdownEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBreakdownEntity extends CreateRecord
{
    protected static string $resource = BreakdownEntityResource::class;

    protected function afterCreate(): void
    {
        // Update meals to include plan_uuid
        $breakdown = $this->record;
        if ($breakdown && $breakdown->plan_uuid) {
            $breakdown->meals()->update(['plan_uuid' => $breakdown->plan_uuid]);
        }
    }
}
