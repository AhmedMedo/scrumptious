<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Handler\Plan;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\MealPlanner\Application\Mapper\Plan\PlanViewModelMapper;
use App\Components\MealPlanner\Application\Service\Plan\PlanServiceInterface;
use App\Components\MealPlanner\Data\Entity\PlanEntity;
use App\Components\MealPlanner\Infrastructure\Http\Request\Plan\CreatePlanRequest;
use App\Libraries\Base\Database\MySQL\ConnectionService;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/plans/{uuid}/show',
    description: 'Show Plan',
    summary: 'Show Plan',
    tags: ['Plan'],
    parameters: [
        new OA\Parameter(
            name: 'uuid',
            description: 'Plan UUID',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'string')
        )
    ],
    responses: [

        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', ref: '#/components/schemas/PlanViewModel', type: 'object'),
            ]
        ))
    ]
)]
class PlanShowHandler extends Handler
{


    public function __construct(
        private readonly PlanServiceInterface $service,
        private readonly PlanViewModelMapper  $mapper,
    )
    {
    }

    public function __invoke(string $uuid): \Illuminate\Http\JsonResponse
    {
        $plan = $this->service->show($uuid);
        return $this->successResponseWithData(
            data: $this->mapper->fromEntity($plan)->toArray(),
        );
    }
}
