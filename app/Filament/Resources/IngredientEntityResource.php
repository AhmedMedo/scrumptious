<?php

namespace App\Filament\Resources;

use App\Components\Recipe\Data\Entity\IngredientEntity;
use App\Filament\Resources\IngredientEntityResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IngredientEntityResource extends Resource
{
    protected static ?string $model = IngredientEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Ingredients';
    protected static ?string $navigationGroup = 'Recipes';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('content')
                ->required()
                ->maxLength(255),

            Forms\Components\Checkbox::make('is_active')
                ->label('Is Active')
                ->default(true),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('uuid')
                ->label('UUID')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('content')
                ->label('Content')
                ->sortable()
                ->searchable(),

            Tables\Columns\IconColumn::make('is_active')
                ->boolean('is_active')
                ->label('Is Active')
                ->sortable(),
        ])
            ->filters([
                // You can add filters here if necessary, e.g. by 'is_active'
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIngredientEntities::route('/'),
            'create' => Pages\CreateIngredientEntity::route('/create'),
            'edit' => Pages\EditIngredientEntity::route('/{record}/edit'),
        ];
    }
}
