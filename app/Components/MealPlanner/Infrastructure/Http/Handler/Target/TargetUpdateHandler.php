<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Handler\Target;

use App\Components\MealPlanner\Application\Service\Target\TargetServiceInterface;
use App\Components\MealPlanner\Infrastructure\Http\Request\Target\UpdateTargetRequest;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;
#[OA\Patch(
    path: '/api/v1/target/{uuid}/update',
    description: 'update target',
    summary: 'update target',
    requestBody: new OA\RequestBody('#/components/requestBodies/CreateTargetRequest'),
    tags: ['Target'],
    parameters: [
        new OA\Parameter(name: 'uuid', description: 'Recipe  UUID', in: 'path', required: true),
    ],
    responses: [

        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
            ],
            type: 'object',
        ))
    ]
)]

class TargetUpdateHandler extends Handler
{
    public function __construct(
        private readonly TargetServiceInterface $service
    )
    {
    }

    public function __invoke(UpdateTargetRequest $request, string $uuid): \Illuminate\Http\JsonResponse
    {
        $target = $this->service->update($uuid, $request->validated());
        return $this->successResponseWithMessage('Target updated successfully');
    }
}
