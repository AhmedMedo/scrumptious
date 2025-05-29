<?php

namespace App\Filament\Resources;

use App\Components\MealPlanner\Data\Entity\TargetEntity;
use App\Filament\Resources\TargetEntityResource\Pages\CreateTargetEntity;
use App\Filament\Resources\TargetEntityResource\Pages\EditTargetEntity;
use App\Filament\Resources\TargetEntityResource\Pages\ListTargetEntities;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class TargetEntityResource extends Resource
{
    protected static ?string $model = TargetEntity::class;

    protected static ?string $navigationLabel = 'Targets';
    protected static ?string $navigationGroup = 'Meal Planning';
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack'; // optional icon

    protected static ?string $modelLabel = 'Target';
    protected static ?string $pluralModelLabel = 'Targets';



    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('user_uuid')
                    ->label('User')
                    ->relationship('user', 'first_name') // Assumes UserEntity has `name` attribute/accessor
                    ->searchable()
                    ->nullable(),

                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->nullable(),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->nullable(),

                TextInput::make('timeframe')
                    ->label('Timeframe')
                    ->nullable(),

                TextInput::make('description')
                    ->label('Description')
                    ->nullable()
                    ->maxLength(65535), // since it's text type
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('End Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('timeframe')
                    ->label('Timeframe')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('User Name')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('user_uuid')
                    ->label('User')
                    ->relationship('user', 'first_name'),
            ])
            ->defaultSort('start_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTargetEntities::route('/'),
            'create' => CreateTargetEntity::route('/create'),
            'edit' => EditTargetEntity::route('/{record}/edit'),
        ];
    }
}
