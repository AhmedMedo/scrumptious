<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Handler\Breakdown;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\MealPlanner\Application\Mapper\Breakdown\BreakdownViewModelMapper;
use App\Components\MealPlanner\Application\Service\Breakdown\BreakdownServiceInterface;
use App\Components\MealPlanner\Infrastructure\Http\Request\Breakdown\CreateBreakdownRequest;
use App\Libraries\Base\Database\MySQL\ConnectionService;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/v1/breakdowns',
    description: 'Create new Meal Plan Breakdown',
    summary: 'Create new Meal Plan Breakdown',
    requestBody: new OA\RequestBody('#/components/requestBodies/CreateBreakdownRequest'),
    tags: ['Breakdown'],
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
class BreakdownCreateHandler extends Handler
{
    public function __construct(
        private readonly BreakdownServiceInterface $service,
        private readonly ConnectionService $connectionService,
        private readonly UserServiceInterface $userService,
        private readonly BreakdownViewModelMapper $mapper
    )
    {
    }

    public function __invoke(CreateBreakdownRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->connectionService->beginTransaction();

            $user = $this->userService->userEntity();
            $breakdown = $this->service->store($request->validated(), $user->uuid);

            $this->connectionService->commit();
            return $this->successResponseWithData(
                data: $this->mapper->fromEntity($breakdown)->toArray(),
                message: 'Breakdown created successfully'
            );
        } catch (\Exception $exception) {
            $this->connectionService->rollback();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
