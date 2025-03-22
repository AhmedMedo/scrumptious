<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Handler\Target;

use App\Components\MealPlanner\Application\Service\Target\TargetServiceInterface;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Delete(
    path: '/api/v1/target/{uuid}/delete',
    description: 'delete target',
    summary: 'delete target',
    tags: ['Target'],
    parameters: [
        new OA\Parameter(name: 'uuid', description: 'Target  UUID', in: 'path', required: true),
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
class TargetDeleteHandler extends Handler
{


    public function __construct(
        private readonly TargetServiceInterface $targetService
    )
    {
    }

    public function __invoke(string $uuid): \Illuminate\Http\JsonResponse
    {
        $this->targetService->delete($uuid);
        return $this->successResponseWithMessage('Target deleted successfully');
    }
}
