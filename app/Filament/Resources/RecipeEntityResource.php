<?php

namespace App\Filament\Resources;

use App\Components\Recipe\Data\Entity\RecipeEntity;
use App\Filament\Resources\RecipeEntityResource\Pages;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Components\MultiSelect;

class RecipeEntityResource extends Resource
{
    protected static ?string $model = RecipeEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Recipes';

    protected static ?string $navigationLabel = 'Recipes';

    protected static ?string $modelLabel = 'Recipe';
    protected static ?string $pluralModelLabel = 'Recipes';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user', 'admin']);
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->title;
    }

    public static function getGlobalSearchQuery(): Builder
    {
        return parent::getGlobalSearchQuery()
            ->with(['user', 'admin'])
            ->where(function ($query) {
                $query
                    ->orWhereHas('user', function ($query) {
                        $query->where('first_name', 'like', '%' . request('search') . '%')
                            ->orWhere('last_name', 'like', '%' . request('search') . '%')
                            ->orWhere('email', 'like', '%' . request('search') . '%');
                    })
                    ->orWhereHas('admin', function ($query) {
                        $query->where('name', 'like', '%' . request('search') . '%')
                            ->orWhere('email', 'like', '%' . request('search') . '%');
                    });
            });
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->label('Recipe Title'),
                Forms\Components\TextInput::make('cooking_minutes')
                    ->numeric()
                    ->label('Cooking Time (minutes)'),
                Forms\Components\TextInput::make('total_carbs')
                    ->numeric()
                    ->label('Total Carbs'),
                Forms\Components\TextInput::make('total_proteins')
                    ->numeric()
                    ->label('Total Proteins'),
                Forms\Components\TextInput::make('total_fats')
                    ->numeric()
                    ->label('Total Fats'),
                Forms\Components\TextInput::make('total_calories')
                    ->numeric()
                    ->label('Total Calories'),
                Forms\Components\TextInput::make('youtube_video')
                    ->url()
                    ->label('YouTube Video URL'),
                Forms\Components\Textarea::make('description')
                    ->label('Description'),
                // Use Filament's FileUpload for handling media
                Forms\Components\FileUpload::make('image')
                    ->label('Recipe Image')
                    ->image()
                    ->disk('public')
                    ->directory('recipes')
                    ->preserveFilenames() // optional
                    ->maxSize(2048) // optional
                    ->dehydrated(false)
                    ->afterStateHydrated(function ($component, $state) {
                        $record = $component->getModelInstance();

                        if ($record && $media = $record->getFirstMedia('image')) {
                            // Wrap in array to match what FileUpload expects
                            $component->state([$media->getPathRelativeToRoot()]);
                        }
                    })
                    ->afterStateUpdated(function ($state, callable $set, callable $get, $record) {
                        if ($state instanceof TemporaryUploadedFile && $record instanceof \App\Components\Recipe\Data\Entity\RecipeEntity) {
                            // Move file to permanent location first
                            $storedPath = $state->store('recipes', 'public');
                            $record->clearMediaCollection('image');
                            // Add to Spatie media from full path
                            $record
                                ->addMedia(storage_path("app/public/{$storedPath}"))
                                ->usingFileName($state->getClientOriginalName())
                                ->preservingOriginal()
                                ->toMediaCollection('image');
                        }
                    }),

                Forms\Components\FileUpload::make('video')
                    ->label('Recipe video')
//                    ->video()
                    ->disk('public')
                    ->directory('recipes')
                    ->preserveFilenames() // optional
//                    ->maxSize(2048) // optional
                    ->dehydrated(false)
                    ->afterStateHydrated(function ($component, $state) {
                        $record = $component->getModelInstance();

                        if ($record && $media = $record->getFirstMedia('video')) {
                            // Wrap in array to match what FileUpload expects
                            $component->state([$media->getPathRelativeToRoot()]);
                        }
                    })
                    ->afterStateUpdated(function ($state, callable $set, callable $get, $record) {
                        if ($state instanceof TemporaryUploadedFile && $record instanceof \App\Components\Recipe\Data\Entity\RecipeEntity) {
                            // Move file to permanent location first
                            $storedPath = $state->store('recipes', 'public');
                            $record->clearMediaCollection('video');
                            // Add to Spatie media from full path
                            $record
                                ->addMedia(storage_path("app/public/{$storedPath}"))
                                ->usingFileName($state->getClientOriginalName())
                                ->preservingOriginal()
                                ->toMediaCollection('video');
                        }
                    }),
                Repeater::make('instructions')
                    ->schema([
                        Textarea::make('content')
                            ->label('Instruction')
                            ->required()
                            ->rules(['string', 'min:5']), // optional: add length or format rules
                    ])
                    ->label('Instructions')
                    ->columns(1)
                    ->reorderable()
                    ->defaultItems(0)
                    ->default([])
                    ->dehydrated(true)
                    ->saveRelationshipsUsing(function (Repeater $component, $state) {
                        // This will be handled in afterSave
                    }),
                Repeater::make('ingredients')
                    ->schema([
                        TextInput::make('content')->label('Ingredient')
                            ->required()
                            ->rules(['string', 'min:5']),
                    ])
                    ->label('Ingredients')
                    ->columns(1)
                    ->reorderable()
                    ->defaultItems(0)
                    ->default([])
                    ->dehydrated(true)
                    ->saveRelationshipsUsing(function (Repeater $component, $state) {
                        // This will be handled in afterSave
                    }),
                MultiSelect::make('categories')
                    ->label('Categories')
                    ->relationship('categories', 'name') // assuming your categories table has a 'name' column
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Is Active')
                    ->default(true),

            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display Recipe Image
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image')
                    ->getStateUsing(function ($record) {
                        return $record->getFirstMediaUrl('image');
                    })
                    ->disk('public')
                    ->height(60)
                    ->circular(),
                // Display Recipe Title
                Tables\Columns\TextColumn::make('title')
                    ->label('Recipe Title')
                    ->sortable(), // Allow sorting by title


                // Display YouTube Video URL (if any)
                Tables\Columns\TextColumn::make('youtube_video')
                    ->label('YouTube Url')
                    ->formatStateUsing(fn () => '<span title="Watch on YouTube">▶️</span>') // emoji as icon
                    ->url(fn ($record) => $record->youtube_video)
                    ->openUrlInNewTab()
                    ->html(),


                // Display User UUID (this would be the user who created the recipe)
                Tables\Columns\TextColumn::make('creator')
                    ->label('Created By')
                    ->getStateUsing(function ($record) {
                        if ($record->admin) {
                            return 'Admin: ' . $record->admin->name;
                        } elseif ($record->user) {
                            return 'User: ' . $record->user->first_name . ' ' . $record->user->last_name;
                        }
                        return 'Unknown';
                    }),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),


                // Display Created At (timestamp)
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('created_by')
                    ->label('Created By')
                    ->options([
                        'admin' => 'Admins',
                        'user' => 'Users',
                    ])
                    ->query(function (Builder $query, array $state) {
                        if ($state['value'] === 'admin') {
                            return $query->whereNotNull('admin_uuid');
                        } elseif ($state['value'] === 'user') {
                            return $query->whereNotNull('user_uuid');
                        }
                        return $query;
                    }),
                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive')
                    ->nullable(), // allows filtering by active/inactive/null

            ])
            ->actions([
                // Define actions for each row (e.g., view, edit, delete)
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true])),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => false])),

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
            'index' => Pages\ListRecipeEntities::route('/'),
            'create' => Pages\CreateRecipeEntity::route('/create'),
            'edit' => Pages\EditRecipeEntity::route('/{record}/edit'),
        ];
    }

}
