<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationEntityResource\Pages;
use App\Filament\Resources\NotificationEntityResource\RelationManagers;
use App\Components\Notification\Data\Entity\NotificationEntity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NotificationEntityResource extends Resource
{
    protected static ?string $model = NotificationEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    protected static ?string $navigationGroup = 'Notifications';
    protected static ?string $navigationLabel = 'Notifications';
    protected static ?string $modelLabel = 'Notification';
    protected static ?string $pluralModelLabel = 'Notifications';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_uuid')
                    ->relationship('user', 'email')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('type')
                    ->options([
                        'meal_plan_customized' => 'Meal Plan Customized',
                        'target_reminder' => 'Target Reminder',
                        'new_recipe' => 'New Recipe',
                        'admin_message' => 'Admin Message',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('body')
                    ->required()
                    ->rows(3),
                Forms\Components\KeyValue::make('data')
                    ->label('Additional Data'),
                Forms\Components\Toggle::make('is_read')
                    ->label('Mark as Read'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'meal_plan_customized',
                        'warning' => 'target_reminder',
                        'success' => 'new_recipe',
                        'info' => 'admin_message',
                    ]),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\IconColumn::make('is_read')
                    ->boolean()
                    ->label('Read'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'meal_plan_customized' => 'Meal Plan Customized',
                        'target_reminder' => 'Target Reminder',
                        'new_recipe' => 'New Recipe',
                        'admin_message' => 'Admin Message',
                    ]),
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Read Status')
                    ->placeholder('All notifications')
                    ->trueLabel('Read')
                    ->falseLabel('Unread'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListNotificationEntities::route('/'),
            'view' => Pages\ViewNotificationEntity::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
