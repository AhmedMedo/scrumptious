<?php

namespace App\Filament\Resources\RecipeEntityResource\Pages;

use App\Filament\Resources\RecipeEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRecipeEntity extends CreateRecord
{
    protected static string $resource = RecipeEntityResource::class;
}
