<?php

namespace App\Filament\Resources;

use App\Components\Content\Data\Entity\CategoryEntity;
use App\Components\Recipe\Data\Entity\GroceryEntity;
use App\Filament\Resources\CategoryEntityResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CategoryEntityResource extends Resource
{
    protected static ?string $model = CategoryEntity::class;
    protected static ?string $navigationGroup = 'Recipes';

    protected static ?string $navigationLabel = 'Categories';
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $modelLabel = 'Category';

    protected static ?string $pluralModelLabel = 'Categories';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->label('Grocery Image')
                    ->image()
                    ->disk('public')
                    ->directory('categories')
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
                        if ($state instanceof TemporaryUploadedFile && $record instanceof CategoryEntity) {
                            $storedPath = $state->store('categories', 'public');
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
            ->filters([
                //
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategoryEntities::route('/'),
            'create' => Pages\CreateCategoryEntity::route('/create'),
            'edit' => Pages\EditCategoryEntity::route('/{record}/edit'),
        ];
    }
}
