<?php

namespace App\Components\MealPlanner\Presentation\ViewModel\Breakdown;

use App\Components\MealPlanner\Data\Entity\MealEntity;
use App\Components\Recipe\Application\Mapper\Recipe\RecipeViewModelMapper;
use App\Components\Recipe\Data\Entity\RecipeEntity;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BreakdownViewModel',
    required: ['uuid', 'plan', 'date', 'meals'],
    properties: [
        new OA\Property(property: 'uuid', type: 'string', example: 'uuid'),
        new OA\Property(property: 'plan', type: 'object', properties: [
            new OA\Property(property: 'uuid', type: 'string', example: 'uuid'),
            new OA\Property(property: 'name', type: 'string', example: 'user@example.com-2025-01-01-2025-01-07'),
            new OA\Property(property: 'start_date', type: 'string', format: 'date', example: '2025-01-01'),
            new OA\Property(property: 'end_date', type: 'string', format: 'date', example: '2025-01-07'),
        ]),
        new OA\Property(property: 'date', type: 'string', format: 'date', example: '2022-01-01'),
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
class BreakdownViewModel
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $planUuid,
        public readonly ?string $planName,
        public readonly Carbon $planStartDate,
        public readonly Carbon $planEndDate,
        public readonly Carbon $date,
        public readonly Collection $meals,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'plan' => [
                'uuid' => $this->planUuid,
                'name' => $this->planName,
                'start_date' => $this->planStartDate->toDateString(),
                'end_date' => $this->planEndDate->toDateString(),
            ],
            'date' => $this->date->toDateString(),
            'meals' => $this->meals->map(fn(MealEntity $meal) => [
                'uuid' => $meal->uuid,
                'type' => $meal->type,
                'recipes' => $meal->recipes
                    ->map(fn(RecipeEntity $recipe) => app(RecipeViewModelMapper::class)->fromEntity($recipe)->toArray())
                    ->toArray(),
            ])->toArray(),
        ];
    }
}

