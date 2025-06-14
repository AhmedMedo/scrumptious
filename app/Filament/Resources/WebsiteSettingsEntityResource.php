<?php

namespace App\Filament\Resources;

use App\Components\Content\Data\Entity\WebsiteSettingsEntity;
use App\Filament\Resources\WebsiteSettingsEntityResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Database\Eloquent\Model;
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
            Forms\Components\TextInput::make('website_name')
                ->label('Website Name')
                ->required(),
            Forms\Components\RichEditor::make('privacy_and_policy_en')
                ->label('Privacy & Policy (English)')
                ->required(),
            Forms\Components\RichEditor::make('privacy_and_policy_ar')
                ->label('Privacy & Policy (Arabic)')
                ->required(),
            Forms\Components\RichEditor::make('terms_and_condition_en')
                ->label('Terms & Conditions (English)')
                ->required(),
            Forms\Components\RichEditor::make('terms_and_condition_ar')
                ->label('Terms & Conditions (Arabic)')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('website_name')->label('Website Name')->sortable()->searchable(),
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

    public static function canCreate(): bool
    {
        return WebsiteSettingsEntity::count() === 0;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
