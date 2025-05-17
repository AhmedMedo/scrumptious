<?php

namespace App\Filament\Resources;

use App\Components\Recipe\Data\Entity\IngredientEntity;
use App\Filament\Resources\IngredientEntityResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class IngredientEntityResource extends Resource
{
    protected static ?string $model = IngredientEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Ingredients';
    protected static ?string $navigationGroup = 'Recipes';

    protected static ?string $modelLabel = 'Ingredient';

    protected static ?string $pluralModelLabel = 'Ingredients';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('content')
                ->required()
                ->maxLength(255),
            Forms\Components\FileUpload::make('image')
                ->label('Ingredient Image')
                ->image()
                ->disk('public')
                ->directory('ingredients')
                ->preserveFilenames() // optional
                ->maxSize(2048) // optional
                ->dehydrated(false) // ✅ Do not try to save 'image' to DB
                ->afterStateHydrated(function ($component, $state) {
                    $record = $component->getModelInstance();

                    if ($record && $media = $record->getFirstMedia('image')) {
                        // Wrap in array to match what FileUpload expects
                        $component->state([$media->getPathRelativeToRoot()]);
                    }
                })
                ->afterStateUpdated(function ($state, callable $set, callable $get, $record) {
                    if ($state instanceof TemporaryUploadedFile && $record instanceof \App\Components\Recipe\Data\Entity\IngredientEntity) {
                        // Move file to permanent location first
                        $storedPath = $state->store('ingredients', 'public');
                        $record->clearMediaCollection('image');
                        // Add to Spatie media from full path
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

    public static function table(Tables\Table $table): Tables\Table
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

            Tables\Columns\TextColumn::make('content')
                ->label('Content')
                ->sortable()
                ->searchable(),

            Tables\Columns\IconColumn::make('is_active')
                ->boolean('is_active')
                ->label('Is Active')
                ->sortable(),
        ])
            ->filters([
                // You can add filters here if necessary, e.g. by 'is_active'
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
            'index' => Pages\ListIngredientEntities::route('/'),
            'create' => Pages\CreateIngredientEntity::route('/create'),
            'edit' => Pages\EditIngredientEntity::route('/{record}/edit'),
        ];
    }
}
