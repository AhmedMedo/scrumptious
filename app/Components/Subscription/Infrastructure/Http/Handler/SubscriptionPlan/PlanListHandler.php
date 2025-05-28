<?php

namespace App\Components\Subscription\Infrastructure\Http\Handler\SubscriptionPlan;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\MealPlanner\Application\Mapper\Plan\PlanViewModelMapper;
use App\Components\MealPlanner\Application\Service\Plan\PlanServiceInterface;
use App\Components\MealPlanner\Data\Entity\PlanEntity;
use App\Components\MealPlanner\Infrastructure\Http\Request\Plan\CreatePlanRequest;
use App\Components\Subscription\Data\Entity\SubscriptionPlanEntity;
use App\Libraries\Base\Database\MySQL\ConnectionService;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/subscription/plans',
    description: 'Get list of subscription plans',
    summary: 'Get list of subscription plans',
    tags: ['Subscription'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'success',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string'),
                    new OA\Property(property: 'message', type: 'string'),
                    new OA\Property(
                        property: 'data',
                        type: 'array',
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'uuid', type: 'string'),
                                new OA\Property(property: 'name', type: 'string'),
                                new OA\Property(property: 'description', type: 'string'),
                                new OA\Property(property: 'price', type: 'number', format: 'float'),
                                new OA\Property(property: 'currency', type: 'string'),
                            ]
                        )
                    ),
                ]
            )
        ),
    ]
)]

class PlanListHandler extends Handler
{


    public function __construct(
    )
    {
    }

    public function __invoke(): \Illuminate\Http\JsonResponse
    {
      return  $this->successResponseWithData(
          data: SubscriptionPlanEntity::query()->where('is_active', 1)->get()->map(fn(SubscriptionPlanEntity $plan) => [
              'uuid' => $plan->uuid,
              'name' => $plan->name,
              'description' => $plan->description,
              'price' => $plan->price,
              'currency' => $plan->currency,
          ])->toArray()
      );
    }
}
