<?php

namespace App\Filament\Resources;

use App\Components\Subscription\Data\Entity\SubscriptionPlanEntity;
use App\Filament\Resources\SubscriptionPlanEntityResource\Pages\CreateSubscriptionPlanEntity;
use App\Filament\Resources\SubscriptionPlanEntityResource\Pages\ListSubscriptionPlanEntities;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;

class SubscriptionPlanEntityResource extends Resource
{
    protected static ?string $model = SubscriptionPlanEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Subscription';

    protected static ?string $navigationLabel = 'Plans';

    protected static ?string $modelLabel = 'Plan';
    protected static ?string $pluralModelLabel = 'Plans';



    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                // Only price is editable
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->label('Price'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->inline(false)
                    ->default(true),

                // Disable editing other fields
                // If you want to display other fields read-only, you can use Disabled inputs
                Forms\Components\TextInput::make('name')->disabled(),
                Forms\Components\TextInput::make('slug')->disabled(),
                Forms\Components\Textarea::make('description')->disabled(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable(),
                Tables\Columns\TextColumn::make('slug')->sortable(),
                Tables\Columns\TextColumn::make('description')->limit(50),
                Tables\Columns\TextColumn::make('price')->money('egp', true), // or just plain number
                Tables\Columns\BooleanColumn::make('is_active')->label('Active'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // No delete action here
            ])
            ->bulkActions([
                // No bulk delete actions
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubscriptionPlanEntities::route('/'),
            'create' => CreateSubscriptionPlanEntity::route('/create'),
            // No create page route
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Disable the create button in the UI
    }
}
