<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Handler\Breakdown;

use App\Components\MealPlanner\Application\Mapper\Breakdown\BreakdownViewModelMapper;
use App\Components\MealPlanner\Application\Service\Breakdown\BreakdownServiceInterface;
use App\Components\MealPlanner\Data\Entity\MealPlanBreakdownEntity;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/breakdowns/list',
    description: 'Get list of Meal Plan Breakdowns',
    summary: 'Get list of Meal Plan Breakdowns',
    tags: ['Breakdown'],
    parameters: [
        new OA\Parameter(
            name: 'plan_uuid',
            description: 'Filter by plan UUID',
            in: 'query',
            required: false,
            schema: new OA\Schema(type: 'string', format: 'uuid')
        ),
        new OA\Parameter(
            name: 'date',
            description: 'Filter by date',
            in: 'query',
            required: false,
            schema: new OA\Schema(type: 'string', format: 'date')
        )
    ],
    responses: [
        new OA\Response(response: 200, description: 'success', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/BreakdownViewModel', type: 'object')),
                new OA\Property(property: 'meta', ref: '#/components/schemas/Meta'),
            ]
        ))
    ]
)]
class BreakdownListHandler extends Handler
{
    public function __construct(
        private readonly BreakdownServiceInterface $service,
        private readonly BreakdownViewModelMapper $mapper,
    )
    {
    }

    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $planUuid = request()->query('plan_uuid');
        $date = request()->query('date');

        $breakdowns = $this->service->paginate($planUuid, $date);
        return $this->successResponseWithDataAndMeta(
            data: $breakdowns->map(fn(MealPlanBreakdownEntity $breakdown) => $this->mapper->fromEntity($breakdown)->toArray())->toArray(),
            meta: [
                'total' => $breakdowns->total(),
                'per_page' => $breakdowns->perPage(),
                'current_page' => $breakdowns->currentPage(),
                'last_page' => $breakdowns->lastPage(),
            ]
        );
    }
}
