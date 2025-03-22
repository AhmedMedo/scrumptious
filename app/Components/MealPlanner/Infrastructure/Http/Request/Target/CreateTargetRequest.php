<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Request\Target;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;
/**
 * Class CreateTargetRequest
 *
 * This class handles the validation and OpenAPI documentation for the request to create a new target.
 *
 * @package App\Components\MealPlanner\Infrastructure\Http\Request\Target
 */
#[OA\RequestBody(
    request: 'CreateTargetRequest',
    required: true,
    content: new OA\JsonContent(
        required: ['start_date', 'timeframe'],
        properties: [
            new OA\Property(property: 'start_date', description: 'The start date for the target.', type: 'string', format: 'date', example: '2025-04-01'),
            new OA\Property(property: 'timeframe', description: 'The timeframe for the target (e.g., "monthly", "quarterly").', type: 'string', example: 'monthly'),
            new OA\Property(property: 'description', description: 'A brief description of the target (optional).', type: 'string', example: 'Complete 20 meals this month', nullable: true),
        ]
    )
)]
class CreateTargetRequest extends FormRequest
{

    public function rules(): array
    {
        return  [
            'start_date' => [
                'required',
                'date',
            ],
            'timeframe' => [
                'required',
                'string',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

}
