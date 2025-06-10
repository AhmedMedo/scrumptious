<?php

namespace App\Filament\Resources;

use App\Components\Recipe\Data\Entity\GroceryEntity;
use App\Components\Recipe\Data\Entity\GroceryCategoryEntity;
use App\Filament\Resources\GroceryEntityResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class GroceryEntityResource extends Resource
{
    protected static ?string $model = GroceryEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Groceries';
    protected static ?string $navigationGroup = 'Recipes';

    protected static ?string $modelLabel = 'Grocery';
    protected static ?string $pluralModelLabel = 'Groceries';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('content')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('category_uuid')
                ->label('Category')
                ->relationship('category', 'name')
                ->required()
                ->searchable()
                ->preload(),

            Forms\Components\FileUpload::make('image')
                ->label('Grocery Image')
                ->image()
                ->disk('public')
                ->directory('groceries')
                ->preserveFilenames()
                ->maxSize(2048)
                ->dehydrated(false)
                ->afterStateHydrated(function ($component, $state) {
                    $record = $component->getModelInstance();
                    if ($record && $media = $record->getFirstMedia('image')) {
                        $component->state([$media->getPathRelativeToRoot()]);
                    }
                })
                ->afterStateUpdated(function ($state, callable $set, callable $get, $record) {
                    if ($state instanceof TemporaryUploadedFile && $record instanceof GroceryEntity) {
                        $storedPath = $state->store('groceries', 'public');
                        $record->clearMediaCollection('image');
                        $record
                            ->addMedia(storage_path("app/public/{$storedPath}"))
                            ->usingFileName($state->getClientOriginalName())
                            ->preservingOriginal()
                            ->toMediaCollection('image');
                    }
                }),

            Forms\Components\Checkbox::make('is_active')
                ->label('Is Active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image')
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('image'))
                    ->disk('public')
                    ->height(60)
                    ->circular(),

                Tables\Columns\TextColumn::make('content')
                    ->label('Content')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Is Active')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_uuid')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable(),
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
            'index' => Pages\ListGroceryEntities::route('/'),
            'create' => Pages\CreateGroceryEntity::route('/create'),
            'edit' => Pages\EditGroceryEntity::route('/{record}/edit'),
        ];
    }
}
