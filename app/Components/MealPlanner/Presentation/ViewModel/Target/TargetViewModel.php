<?php

namespace App\Components\MealPlanner\Presentation\ViewModel\Target;

use Carbon\Carbon;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TargetViewModel',
    required: ['uuid', 'start_date', 'end_date', 'timeframe'],
    properties: [
        new OA\Property(property: 'uuid', type: 'string', example: 'uuid'),
        new OA\Property(property: 'title', type: 'string', example: 'title'),
        new OA\Property(property: 'start_date', type: 'string', example: '2022-01-01'),
        new OA\Property(property: 'end_date', type: 'string', example: '2022-01-01'),
        new OA\Property(property: 'timeframe', type: 'string', example: 'week'),
        new OA\Property(property: 'description', type: 'string', example: 'description'),

    ]
)]
class TargetViewModel
{

    public function __construct(
        public readonly string  $uuid,
        public readonly ?string  $title = null,
        public readonly Carbon  $startDate,
        public readonly Carbon  $endDate,
        public readonly string  $timeframe,
        public readonly ?string $description = null
    )
    {
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'timeframe' => $this->timeframe,
            'description' => $this->description,
        ];

    }
}
