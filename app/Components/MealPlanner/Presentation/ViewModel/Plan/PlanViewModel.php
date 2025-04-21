<?php

namespace App\Components\MealPlanner\Presentation\ViewModel\Plan;
use App\Components\MealPlanner\Data\Entity\MealEntity;
use App\Components\Recipe\Application\Mapper\Recipe\RecipeViewModelMapper;
use App\Components\Recipe\Data\Entity\RecipeEntity;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'PlanViewModel',
    required: ['uuid', 'start_date', 'end_date', 'type', 'meals'],
    properties: [
        new OA\Property(property: 'uuid', type: 'string', example: 'uuid'),
        new OA\Property(property: 'start_date', type: 'string', example: '2022-01-01'),
        new OA\Property(property: 'end_date', type: 'string', example: '2022-01-01'),
        new OA\Property(property: 'type', type: 'string', example: 'week'),
        new OA\Property(property: 'meals', type: 'array', items: new OA\Items(
            properties: [
                new OA\Property(property: 'uuid', type: 'string', example: 'uuid'),
                new OA\Property(property: 'type', type: 'string', example: 'breakfast'),
                new OA\Property(property: 'recipes', type: 'array', items: new OA\Items(ref: '#/components/schemas/RecipeViewModel', type: 'object')),
            ],
            type: 'object'
        )),
    ],
    type: 'object'
)]


class PlanViewModel
{


    public function __construct(
        public readonly string  $uuid,
        public readonly Carbon  $startDate,
        public readonly Carbon  $endDate,
        public readonly string  $type,
        public readonly Collection $meals,
    )
    {
    }


    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'start_date' => $this->startDate->toDateString(),
            'end_date' => $this->endDate->toDateString(),
            'type' => $this->type,
            'meals' => $this->meals->map(fn( MealEntity$meal) => [
                'uuid' => $meal->uuid,
                'type' => $meal->type,
                'recipes' => $meal->recipes
                    ->map(fn( RecipeEntity $recipe) => app(RecipeViewModelMapper::class)->fromEntity($recipe)->toArray())
                    ->toArray(),
            ])->toArray(),
        ];
    }
}
