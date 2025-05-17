<?php

namespace App\Filament\Resources;

use App\Components\MealPlanner\Data\Entity\PlanEntity;
use App\Filament\Resources\PlanEntityResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Builder;

class PlanEntityResource extends Resource
{
    protected static ?string $model = PlanEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Meal Plans';
    protected static ?string $navigationGroup = 'Meal Planning';

    protected static ?string $modelLabel = 'Plan';
    protected static ?string $pluralModelLabel = 'Plans';

    public static function form(Form $form): Form
    {
        return $form->schema([
        Select::make('user_uuid')
            ->label('Select User')
            ->relationship('user', 'email')
            ->searchable()
            ->required(),

        Hidden::make('admin_uuid')
            ->default(fn () => auth('admin')->user()?->uuid),

        Select::make('type')
            ->label('Plan Type')
            ->options([
                'weekly' => 'Weekly',
                'monthly' => 'Monthly',
                'yearly' => 'Yearly',
            ])
            ->required(),

        DatePicker::make('start_date')
            ->required()
            ->native(false)
            ->format('Y-m-d')
            ->displayFormat('Y-m-d')
            ->minDate(now()->startOfDay()),

        DatePicker::make('end_date')
            ->required()
            ->native(false)
            ->format('Y-m-d')
            ->displayFormat('Y-m-d')
            ->afterOrEqual('start_date'),

        Repeater::make('meals')
            ->label('Meals')
            ->relationship()
            ->schema([
                Select::make('type')
                    ->label('Meal Type')
                    ->options([
                        'breakfast' => 'Breakfast',
                        'lunch' => 'Lunch',
                        'dinner' => 'Dinner',
                    ])
                    ->required(),

                Select::make('recipes')
                    ->label('Recipes')
                    ->multiple()
                    ->relationship('recipes', 'title')
                    ->preload()
                    ->required(),
            ])
            ->columns(2)
            ->createItemButtonLabel('Add Meal')
    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('user.email')->label('User'),
            Tables\Columns\TextColumn::make('admin.name')->label('Created By Admin'),
            Tables\Columns\TextColumn::make('type')->label('Plan Type'),
            Tables\Columns\TextColumn::make('start_date')->date(),
            Tables\Columns\TextColumn::make('end_date')->date(),
            Tables\Columns\TextColumn::make('meals_count')->counts('meals')->label('Meals'),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlanEntities::route('/'),
            'create' => Pages\CreatePlanEntity::route('/create'),
            'edit' => Pages\EditPlanEntity::route('/{record}/edit'),
        ];
    }
}
