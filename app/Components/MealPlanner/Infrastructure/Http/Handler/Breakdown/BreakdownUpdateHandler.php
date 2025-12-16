<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Handler\Breakdown;

use App\Components\MealPlanner\Application\Mapper\Breakdown\BreakdownViewModelMapper;
use App\Components\MealPlanner\Application\Service\Breakdown\BreakdownServiceInterface;
use App\Components\MealPlanner\Infrastructure\Http\Request\Breakdown\UpdateBreakdownRequest;
use App\Libraries\Base\Database\MySQL\ConnectionService;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Patch(
    path: '/api/v1/breakdowns/{uuid}/update',
    description: 'Update Meal Plan Breakdown',
    summary: 'Update Meal Plan Breakdown',
    requestBody: new OA\RequestBody('#/components/requestBodies/UpdateBreakdownRequest'),
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
class BreakdownUpdateHandler extends Handler
{
    public function __construct(
        private readonly BreakdownServiceInterface $service,
        private readonly ConnectionService $connectionService,
        private readonly BreakdownViewModelMapper $mapper
    )
    {
    }

    public function __invoke(string $uuid, UpdateBreakdownRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->connectionService->beginTransaction();
            $breakdown = $this->service->update($uuid, $request->validated());
            $this->connectionService->commit();
            return $this->successResponseWithData(
                data: $this->mapper->fromEntity($breakdown)->toArray(),
                message: 'Breakdown updated successfully'
            );
        } catch (\Exception $exception) {
            $this->connectionService->rollback();
            return $this->errorResponse($exception->getMessage());
        }
    }
}

