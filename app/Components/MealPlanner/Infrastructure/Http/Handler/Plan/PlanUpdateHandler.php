<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Handler\Plan;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\MealPlanner\Application\Service\Plan\PlanServiceInterface;
use App\Components\MealPlanner\Infrastructure\Http\Request\Plan\UpdatePlanRequest;
use App\Libraries\Base\Database\MySQL\ConnectionService;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Patch(
    path: '/api/v1/{uuid}/plans',

    description: 'update Plan',
    summary: 'update Plan',
    requestBody: new OA\RequestBody('#/components/requestBodies/CreatePlanRequest'),
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

            ]
        ))
    ]
)]
class PlanUpdateHandler extends Handler
{
    public function __construct(
        private readonly PlanServiceInterface $service,
        private readonly UserServiceInterface $userService,
        private readonly ConnectionService    $connectionService
    )
    {
    }

    public function __invoke(string $uuid, UpdatePlanRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->connectionService->beginTransaction();
            $data = array_merge($request->validated(), ['user_uuid' => $this->userService->user()->uuid()]);
            $this->service->update($uuid, $data);
            $this->connectionService->commit();
            return $this->successResponseWithMessage('Plan updated successfully');
        } catch (\Exception $exception) {
            $this->connectionService->rollback();
            return $this->errorResponse($exception->getMessage());
        }

    }
}
