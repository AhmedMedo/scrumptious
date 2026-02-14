<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminBroadcastEntityResource\Pages;
use App\Filament\Resources\AdminBroadcastEntityResource\RelationManagers;
use App\Components\Notification\Data\Entity\AdminBroadcastEntity;
use App\Components\Auth\Data\Entity\UserEntity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdminBroadcastEntityResource extends Resource
{
    protected static ?string $model = AdminBroadcastEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationGroup = 'Notifications';
    protected static ?string $navigationLabel = 'Broadcasts';
    protected static ?string $modelLabel = 'Broadcast';
    protected static ?string $pluralModelLabel = 'Broadcasts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('body')
                    ->required()
                    ->rows(4),
                Forms\Components\KeyValue::make('data')
                    ->label('Additional Data'),
                Forms\Components\Select::make('target_type')
                    ->options([
                        'all' => 'All Users',
                        'specific' => 'Specific Users',
                    ])
                    ->required()
                    ->reactive(),
                Forms\Components\Select::make('target_user_uuids')
                    ->label('Target Users')
                    ->multiple()
                    ->searchable()
                    ->options(UserEntity::pluck('email', 'uuid')->toArray())
                    ->visible(fn (callable $get) => $get('target_type') === 'specific'),
                Forms\Components\DateTimePicker::make('scheduled_at')
                    ->label('Schedule For')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('target_type')
                    ->badge()
                    ->colors([
                        'success' => 'all',
                        'warning' => 'specific',
                    ]),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'scheduled',
                        'success' => 'sent',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('total_recipients')
                    ->label('Recipients'),
                Tables\Columns\TextColumn::make('successful_sends')
                    ->label('Sent'),
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                    ]),
                Tables\Filters\SelectFilter::make('target_type')
                    ->options([
                        'all' => 'All Users',
                        'specific' => 'Specific Users',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAdminBroadcastEntities::route('/'),
            'create' => Pages\CreateAdminBroadcastEntity::route('/create'),
            'edit' => Pages\EditAdminBroadcastEntity::route('/{record}/edit'),
        ];
    }
}
