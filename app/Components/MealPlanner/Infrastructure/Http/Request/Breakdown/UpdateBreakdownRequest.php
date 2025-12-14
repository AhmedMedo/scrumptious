<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Request\Breakdown;

use App\Components\MealPlanner\Data\Entity\MealPlanBreakdownEntity;
use App\Components\MealPlanner\Data\Entity\PlanEntity;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\RequestBody(
    request: 'UpdateBreakdownRequest',
    required: true,
    content: new OA\JsonContent(
        required: ['date', 'meals'],
        properties: [
            new OA\Property(
                property: 'date',
                description: 'The date for this breakdown (must be within plan date range).',
                type: 'string',
                format: 'date',
                example: '2025-04-01'
            ),
            new OA\Property(
                property: 'meals',
                description: 'An array of meal objects.',
                type: 'array',
                items: new OA\Items(
                    properties: [
                        new OA\Property(
                            property: 'type',
                            description: 'The type of meal (e.g., "breakfast", "lunch", "dinner").',
                            type: 'string',
                            example: 'breakfast'
                        ),
                        new OA\Property(
                            property: 'recipes',
                            description: 'An array of recipe UUIDs.',
                            type: 'array',
                            items: new OA\Items(
                                type: 'string',
                                format: 'uuid',
                                example: 'f5f5f5f5-f5f5-f5f5-f5f5-f5f5f5f5f5f5',
                                nullable: true
                            )
                        )
                    ],
                    type: 'object'
                )
            )
        ]
    )
)]
class UpdateBreakdownRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $breakdown = MealPlanBreakdownEntity::find($this->route('uuid'));
                    if (!$breakdown) {
                        return;
                    }
                    $plan = $breakdown->plan;
                    if (!$plan) {
                        return;
                    }
                    if ($value < $plan->start_date || $value > $plan->end_date) {
                        $fail('The date must be within the plan\'s start date and end date.');
                    }
                },
            ],
            'meals' => [
                'required',
                'array',
            ],
            'meals.*.type' => [
                'required',
                'string',
                'in:breakfast,lunch,dinner',
            ],
            'meals.*.recipes' => [
                'required',
                'array',
            ],
            'meals.*.recipes.*' => [
                'required',
                'uuid',
                'exists:recipes,uuid',
            ],
        ];
    }
}
