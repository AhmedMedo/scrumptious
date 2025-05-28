<?php

namespace App\Filament\Resources\SubscriptionPlanEntityResource\Pages;

use App\Filament\Resources\SubscriptionPlanEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubscriptionPlanEntity extends EditRecord
{
    protected static string $resource = SubscriptionPlanEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
