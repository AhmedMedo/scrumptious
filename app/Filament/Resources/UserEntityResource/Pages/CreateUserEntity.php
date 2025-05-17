<?php

namespace App\Filament\Resources\UserEntityResource\Pages;

use App\Filament\Resources\UserEntityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserEntity extends CreateRecord
{
    protected static string $resource = UserEntityResource::class;

    protected function getCreateButtonLabel(): string
    {
        return 'Add User'; // ✅ This renames the "Create" button
    }
}
