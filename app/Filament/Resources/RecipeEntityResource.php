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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Components\MultiSelect;

class RecipeEntityResource extends Resource
{
    protected static ?string $model = RecipeEntity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Recipes';

    protected static ?string $navigationLabel = 'Recipes';

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

                Repeater::make('instructions')
                    ->schema([
                        Textarea::make('content')
                            ->label('Instruction')
                            ->required()
                            ->rules(['string', 'min:5']), // optional: add length or format rules
                    ])
                    ->label('Instructions')
                    ->columns(1)
                    ->afterStateHydrated(function (Repeater $component) {
                        $component->state(
                            $component->getModelInstance()->instructions->map(function ($instruction) {
                                return ['content' => $instruction->content];
                            })->toArray()
                        );
                    })
                    ->dehydrated(false),
                Repeater::make('ingredients')
                    ->schema([
                        TextInput::make('content')->label('Ingredient')
                            ->required()
                            ->rules(['string', 'min:5']),
                    ])
                    ->label('Ingredients')
                    ->columns(1)
                    ->afterStateHydrated(function ($component) {
                        $component->state(
                            $component->getModelInstance()->ingredients->map(function ($ingredient) {
                                return [
                                    'content' => $ingredient->content, // now we access the actual ingredient field
                                ];
                            })->toArray()
                        );
                    })
                    ->dehydrated(false),
                MultiSelect::make('categories')
                    ->label('Categories')
                    ->relationship('categories', 'name') // assuming your categories table has a 'name' column
                    ->preload()
                    ->searchable()
                    ->required(),
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
                    ->formatStateUsing(fn ($state) => 'Watch')
                    ->url(fn ($record) => $record->youtube_video)
                    ->openUrlInNewTab(),


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
                    })
            ])
            ->actions([
                // Define actions for each row (e.g., view, edit, delete)
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
            'index' => Pages\ListRecipeEntities::route('/'),
            'create' => Pages\CreateRecipeEntity::route('/create'),
            'edit' => Pages\EditRecipeEntity::route('/{record}/edit'),
        ];
    }

}
