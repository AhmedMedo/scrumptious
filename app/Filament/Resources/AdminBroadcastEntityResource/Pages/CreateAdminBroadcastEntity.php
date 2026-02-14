<?php

namespace App\Filament\Resources\AdminBroadcastEntityResource\Pages;

use App\Components\Notification\Data\Enums\BroadcastStatusEnum;
use App\Filament\Resources\AdminBroadcastEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdminBroadcastEntity extends CreateRecord
{
    protected static string $resource = AdminBroadcastEntityResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!isset($data['admin_uuid']) && auth()->guard('admin')->check()) {
            $data['admin_uuid'] = auth()->guard('admin')->user()->uuid;
        }

        if (empty($data['scheduled_at'])) {
            $data['scheduled_at'] = now();
        }

        $data['status'] = BroadcastStatusEnum::SCHEDULED->value;

        return $data;
    }
}
