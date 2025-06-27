<?php

namespace App\Filament\Resources;

use App\Components\Subscription\Data\Entity\PaymobPaymentEntity;
use App\Filament\Resources\PaymobPaymentEntityResource\Pages\ListPaymobPaymentEntities;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymobPaymentEntityResource extends Resource
{
    protected static ?string $model = PaymobPaymentEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Subscription';
    protected static ?string $navigationLabel = 'Payments';
    protected static ?string $modelLabel = 'Payment';
    protected static ?string $pluralModelLabel = 'Payments';

    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')->label('User'),
                Tables\Columns\TextColumn::make('plan.name')->label('Plan'),
                Tables\Columns\TextColumn::make('amount')->money('egp', true),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymobPaymentEntities::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
