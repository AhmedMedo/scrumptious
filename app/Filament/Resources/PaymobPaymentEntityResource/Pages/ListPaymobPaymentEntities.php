<?php

namespace App\Filament\Resources\PaymobPaymentEntityResource\Pages;

use App\Filament\Resources\PaymobPaymentEntityResource;
use Filament\Resources\Pages\ListRecords;

class ListPaymobPaymentEntities extends ListRecords
{
    protected static string $resource = PaymobPaymentEntityResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
