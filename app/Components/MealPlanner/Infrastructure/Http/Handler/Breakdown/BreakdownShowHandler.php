<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Handler\Breakdown;

use App\Components\MealPlanner\Application\Mapper\Breakdown\BreakdownViewModelMapper;
use App\Components\MealPlanner\Application\Service\Breakdown\BreakdownServiceInterface;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/breakdowns/{uuid}/show',
    description: 'Show Meal Plan Breakdown',
    summary: 'Show Meal Plan Breakdown',
    tags: ['Breakdown'],
    parameters: [
        new OA\Parameter(
            name: 'uuid',
            description: 'Breakdown UUID',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'string')
        )
    ],
    responses: [
        new OA\Response(response: 200, description: 'success', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', ref: '#/components/schemas/BreakdownViewModel', type: 'object'),
            ]
        ))
    ]
)]
class BreakdownShowHandler extends Handler
{
    public function __construct(
        private readonly BreakdownServiceInterface $service,
        private readonly BreakdownViewModelMapper $mapper,
    )
    {
    }

    public function __invoke(string $uuid): \Illuminate\Http\JsonResponse
    {
        $breakdown = $this->service->show($uuid);
        return $this->successResponseWithData(
            data: $this->mapper->fromEntity($breakdown)->toArray(),
        );
    }
}

