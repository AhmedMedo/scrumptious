<?php

namespace App\Filament\Resources\IngredientEntityResource\Pages;

use App\Filament\Resources\IngredientEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIngredientEntity extends CreateRecord
{
    protected static string $resource = IngredientEntityResource::class;
}
