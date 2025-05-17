<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserEntityResource\Pages;
use App\Filament\Resources\UserEntityResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserEntityResource extends Resource
{
    protected static ?string $model = \App\Components\Auth\Data\Entity\UserEntity::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Users Management';

    protected static ?string $navigationLabel = 'App Users';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('first_name')->required(),
            Forms\Components\TextInput::make('last_name')->required(),
            Forms\Components\TextInput::make('email')->required()->email()->unique(),
            Forms\Components\TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn($state) => bcrypt($state))
                ->required(fn($context) => $context === 'create'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('first_name'),
            Tables\Columns\TextColumn::make('last_name'),
            Tables\Columns\TextColumn::make('email'),
        ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserEntities::route('/'),
            'create' => Pages\CreateUserEntity::route('/create'),
            'edit' => Pages\EditUserEntity::route('/{record}/edit'),
        ];
    }
}
