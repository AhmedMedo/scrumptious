<?php

namespace App\Filament\Resources;

use App\Components\Content\Data\Entity\WebsiteSettingsEntity;
use App\Filament\Resources\WebsiteSettingsEntityResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WebsiteSettingsEntityResource extends Resource
{
    protected static ?string $model = WebsiteSettingsEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $navigationLabel = 'Website Settings';
    protected static ?string $modelLabel = 'Website Setting';
    protected static ?string $pluralModelLabel = 'Website Settings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('key')
                ->required()
                ->unique(ignoreRecord: true)
                ->disabled(fn ($record) => $record !== null),
            Forms\Components\RichEditor::make('value.en')
                ->label('Value (English)')
                ->required(),
            Forms\Components\RichEditor::make('value.ar')
                ->label('Value (Arabic)')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->sortable()->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWebsiteSettingsEntities::route('/'),
            'create' => Pages\CreateWebsiteSettingsEntity::route('/create'),
            'edit' => Pages\EditWebsiteSettingsEntity::route('/{record}/edit'),
        ];
    }
}
