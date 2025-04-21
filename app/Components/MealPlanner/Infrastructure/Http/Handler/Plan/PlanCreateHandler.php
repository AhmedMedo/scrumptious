<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Handler\Plan;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\MealPlanner\Application\Service\Plan\PlanServiceInterface;
use App\Components\MealPlanner\Infrastructure\Http\Request\Plan\CreatePlanRequest;
use App\Libraries\Base\Database\MySQL\ConnectionService;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/v1/plans',

    description: 'Create new Plan',
    summary: 'Create new Plan',
    requestBody: new OA\RequestBody('#/components/requestBodies/CreatePlanRequest'),
    tags: ['Plan'],
    responses: [

        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),

            ]
        ))
    ]
)]
class PlanCreateHandler extends Handler
{


    public function __construct(
        private readonly PlanServiceInterface $service,
        private readonly UserServiceInterface $userService,
        private readonly ConnectionService    $connectionService
    )
    {
    }

    public function __invoke(CreatePlanRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->connectionService->beginTransaction();
            $data = array_merge($request->validated(), ['user_uuid' => $this->userService->user()->uuid()]);
            $this->service->store($data);
            $this->connectionService->commit();
            return $this->successResponseWithMessage('Plan created successfully');
        } catch (\Exception $exception) {
            $this->connectionService->rollback();
            return $this->errorResponse($exception->getMessage());
        }

    }
}
