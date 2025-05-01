<?php

namespace App\Filament\Resources\CategoryEntityResource\Pages;

use App\Filament\Resources\CategoryEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoryEntity extends CreateRecord
{
    protected static string $resource = CategoryEntityResource::class;
}
