<?php

namespace App\Filament\Resources\AdminBroadcastEntityResource\Pages;

use App\Components\Notification\Data\Enums\BroadcastStatusEnum;
use App\Filament\Resources\AdminBroadcastEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdminBroadcastEntity extends EditRecord
{
    protected static string $resource = AdminBroadcastEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!isset($data['admin_uuid']) && auth()->guard('admin')->check()) {
            $data['admin_uuid'] = auth()->guard('admin')->user()->uuid;
        }

        if (empty($data['scheduled_at'])) {
            $data['scheduled_at'] = now();
        }

        if (! $this->record || $this->record->status !== BroadcastStatusEnum::SENT) {
            $data['status'] = BroadcastStatusEnum::SCHEDULED->value;
        }

        return $data;
    }
}
