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
    path: '/api/v1/plans/list',
    description: 'Get list of Plans',
    summary: 'Get list of Plans',
    tags: ['Plan'],
    responses: [

        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/PlanViewModel', type: 'object')),
                new OA\Property(property: 'meta', ref: '#/components/schemas/Meta'),
            ]
        ))
    ]
)]
class PlanListHandler extends Handler
{


    public function __construct(
        private readonly PlanServiceInterface $service,
        private readonly PlanViewModelMapper   $mapper,
        private readonly UserServiceInterface $userService,
    )
    {
    }

    public function __invoke(): \Illuminate\Http\JsonResponse
    {
      $plans =  $this->service->paginate($this->userService->user()->uuid());
      return  $this->successResponseWithDataAndMeta(
          data: $plans->map(fn(PlanEntity $plan) => $this->mapper->fromEntity($plan)->toArray())->toArray(),
          meta: [
              'total' => $plans->total(),
              'per_page' => $plans->perPage(),
              'current_page' => $plans->currentPage(),
              'last_page' => $plans->lastPage(),

          ]
      );
    }
}
