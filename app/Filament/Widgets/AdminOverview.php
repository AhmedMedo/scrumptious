<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Recipe\Data\Entity\RecipeEntity;
use App\Components\MealPlanner\Data\Entity\PlanEntity;

class AdminOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Users', UserEntity::count())
                ->description('Total registered users')
                ->icon('heroicon-o-user-group'),

            Card::make('Recipes', RecipeEntity::count())
                ->description('Total recipes')
                ->icon('heroicon-o-book-open'),

            Card::make('Meal Plans', PlanEntity::count())
                ->description('Plans created')
                ->icon('heroicon-o-calendar'),
        ];
    }
}
