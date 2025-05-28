<?php

namespace App\Filament\Resources\SubscriptionPlanEntityResource\Pages;

use App\Filament\Resources\SubscriptionPlanEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubscriptionPlanEntities extends ListRecords
{
    protected static string $resource = SubscriptionPlanEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
