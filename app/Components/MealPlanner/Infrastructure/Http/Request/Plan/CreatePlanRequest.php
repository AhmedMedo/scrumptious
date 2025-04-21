<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Request\Plan;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;
#[OA\RequestBody(
    request: 'CreatePlanRequest',
    required: true,
    content: new OA\JsonContent(
        required: ['type', 'start_date', 'end_date', 'meals'],
        properties: [
            new OA\Property(
                property: 'type',
                description: 'The type of plan (e.g., "weekly", "monthly", "yearly").',
                type: 'string',
                example: 'weekly'
            ),
            new OA\Property(
                property: 'start_date',
                description: 'The start date for the plan.',
                type: 'string',
                format: 'date',
                example: '2025-04-01'
            ),
            new OA\Property(
                property: 'end_date',
                description: 'The end date for the plan.',
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
class CreatePlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                'in:weekly,monthly,yearly',
            ],
            'start_date' => [
                'required',
                'after_or_equal:today',
                'date',
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
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
