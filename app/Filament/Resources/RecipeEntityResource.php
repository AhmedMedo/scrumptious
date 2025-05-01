<?php

namespace App\Filament\Resources;

use App\Components\Recipe\Data\Entity\RecipeEntity;
use App\Filament\Resources\RecipeEntityResource\Pages;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Arr;

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
                    ->disk('public') // You can specify the disk here
                    ->directory('recipes') // Optionally specify a folder for the uploaded images
                    ->afterStateUpdated(function (callable $set,$get,$record) {
                        $image = $get('image'); // Get the image from form state
                        if ($image && $record instanceof RecipeEntity) {
                            $record->addMediaFromDisk($image->getPathname(), 'public') // Store the media
                            ->toMediaCollection('image');
                        }
                    }),
                Forms\Components\Repeater::make('instructions')
                    ->schema([
                        Forms\Components\Textarea::make('content')->label('Instruction'),
                    ])
                    ->label('Instructions')
                    ->columns(1),
                Forms\Components\Repeater::make('ingredients')
                    ->schema([
                        Forms\Components\TextInput::make('content')->label('Ingredient'),
                    ])
                    ->label('Ingredients')
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Display Recipe Title
                Tables\Columns\TextColumn::make('title')
                    ->label('Recipe Title')
                    ->sortable(), // Allow sorting by title

                // Display Cooking Minutes (Time)
                Tables\Columns\TextColumn::make('cooking_minutes')
                    ->label('Cooking Minutes')
                    ->sortable(),

                // Display Total Carbs
                Tables\Columns\TextColumn::make('total_carbs')
                    ->label('Total Carbs (g)')
                    ->sortable(),

                // Display Total Proteins
                Tables\Columns\TextColumn::make('total_proteins')
                    ->label('Total Proteins (g)')
                    ->sortable(),

                // Display Total Fats
                Tables\Columns\TextColumn::make('total_fats')
                    ->label('Total Fats (g)')
                    ->sortable(),

                // Display Total Calories
                Tables\Columns\TextColumn::make('total_calories')
                    ->label('Total Calories')
                    ->sortable(),

                // Display YouTube Video URL (if any)
                Tables\Columns\TextColumn::make('youtube_video')
                    ->label('YouTube Video')
                    ->url('youtube_video') // This will make it clickable
                    ->sortable(),

                // Display Recipe Description
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50), // Limit the length for a better table view

                // Display Recipe Image
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public'),

                // Display User UUID (this would be the user who created the recipe)
                Tables\Columns\TextColumn::make('user_uuid')
                    ->label('User UUID')
                    ->sortable(),

                // Display Created At (timestamp)
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At'),

                // Display Updated At (timestamp)
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At'),
            ])
            ->filters([
                // Filters can be added here if needed (e.g., filter by date or status)
            ])
            ->actions([
                // Define actions for each row (e.g., view, edit, delete)
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
