<?php

namespace App\Filament\Resources;

use App\Components\Recipe\Data\Entity\GroceryCategoryEntity;
use App\Filament\Resources\GroceryCategoryEntityResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class GroceryCategoryEntityResource extends Resource
{
    protected static ?string $model = GroceryCategoryEntity::class;
    protected static ?string $navigationGroup = 'Recipes';

    protected static ?string $navigationLabel = 'Grocery Categories';
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $modelLabel = 'Grocery Category';

    protected static ?string $pluralModelLabel = 'Grocery Categories';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\FileUpload::make('image')
                ->label('Image')
                ->image()
                ->disk('public')
                ->directory('grocery-categories')
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
                    if ($state instanceof TemporaryUploadedFile && $record instanceof GroceryCategoryEntity) {
                        $storedPath = $state->store('grocery-categories', 'public');
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
        return $table->columns([
            Tables\Columns\ImageColumn::make('image_url')
                ->label('Image')
                ->getStateUsing(function ($record) {
                    return $record->getFirstMediaUrl('image');
                })
                ->disk('public')
                ->height(60)
                ->circular(),
            Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->sortable()
                ->searchable(),
            Tables\Columns\IconColumn::make('is_active')
                ->boolean('is_active')
                ->label('Is Active')
                ->sortable(),
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
            'index' => Pages\ListGroceryCategoryEntities::route('/'),
            'create' => Pages\CreateGroceryCategoryEntity::route('/create'),
            'edit' => Pages\EditGroceryCategoryEntity::route('/{record}/edit'),
        ];
    }
}
