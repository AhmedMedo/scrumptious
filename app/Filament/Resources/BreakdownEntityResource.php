<?php

namespace App\Filament\Resources;

use App\Components\MealPlanner\Data\Entity\MealPlanBreakdownEntity;
use App\Filament\Resources\BreakdownEntityResource\Pages;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BreakdownEntityResource extends Resource
{
    protected static ?string $model = MealPlanBreakdownEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Meal Plan Breakdowns';
    protected static ?string $navigationGroup = 'Meal Planning';

    protected static ?string $modelLabel = 'Breakdown';
    protected static ?string $pluralModelLabel = 'Breakdowns';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('plan_uuid')
                ->label('Select Plan')
                ->relationship('plan', 'name', fn ($query) => $query->orderBy('start_date', 'desc'))
                ->searchable()
                ->required(),

            DatePicker::make('date')
                ->required()
                ->native(false)
                ->format('Y-m-d')
                ->displayFormat('Y-m-d')
                ->reactive()
                ->minDate(function ($get) {
                    $planUuid = $get('plan_uuid');
                    if (!$planUuid) {
                        return now();
                    }
                    $plan = \App\Components\MealPlanner\Data\Entity\PlanEntity::find($planUuid);
                    return $plan ? $plan->start_date : now();
                })
                ->maxDate(function ($get) {
                    $planUuid = $get('plan_uuid');
                    if (!$planUuid) {
                        return null;
                    }
                    $plan = \App\Components\MealPlanner\Data\Entity\PlanEntity::find($planUuid);
                    return $plan ? $plan->end_date : null;
                }),

            Repeater::make('meals')
                ->label('Meals')
                ->relationship()
                ->mutateRelationshipDataBeforeCreateUsing(function (array $data, $record): array {
                    // Set plan_uuid from the breakdown's plan_uuid
                    // During creation, $record might not be saved yet, so get from form data
                    if ($record && $record->plan_uuid) {
                        $data['plan_uuid'] = $record->plan_uuid;
                    } elseif ($record && isset($record->getAttributes()['plan_uuid'])) {
                        $data['plan_uuid'] = $record->getAttributes()['plan_uuid'];
                    }
                    return $data;
                })
                ->mutateRelationshipDataBeforeSaveUsing(function (array $data, $record): array {
                    // Set plan_uuid from the breakdown's plan_uuid
                    if ($record && $record->plan_uuid) {
                        $data['plan_uuid'] = $record->plan_uuid;
                    }
                    return $data;
                })
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
                Tables\Columns\TextColumn::make('plan.type')->label('Plan Type'),
                Tables\Columns\TextColumn::make('plan.user.email')->label('User'),
                Tables\Columns\TextColumn::make('date')->date()->sortable(),
                Tables\Columns\TextColumn::make('meals_count')->counts('meals')->label('Meals'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('plan_uuid')
                    ->label('Plan')
                    ->relationship('plan', 'type')
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBreakdownEntities::route('/'),
            'create' => Pages\CreateBreakdownEntity::route('/create'),
            'edit' => Pages\EditBreakdownEntity::route('/{record}/edit'),
        ];
    }
}
