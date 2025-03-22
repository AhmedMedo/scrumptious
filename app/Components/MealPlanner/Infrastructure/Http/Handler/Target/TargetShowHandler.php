<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Handler\Target;

use App\Components\MealPlanner\Application\Mapper\Target\TargetViewModelMapper;
use App\Components\MealPlanner\Application\Service\Target\TargetServiceInterface;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;
#[OA\Get(
    path: '/api/v1/target/{uuid}/show',
    summary: 'Show Target',
    tags: ['Target'],
    parameters: [
        new OA\Parameter(
            name: 'uuid',
            in: 'path',
            required: true
        )
    ],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', ref: '#/components/schemas/TargetViewModel'),
            ],
            type      : 'object'
        )),
    ]

)]
class TargetShowHandler extends Handler
{


    public function __construct(
        private readonly TargetServiceInterface $service,
        private readonly TargetViewModelMapper $targetViewModelMapper
    )
    {
    }

    public function __invoke(string $uuid): \Illuminate\Http\JsonResponse
    {
        $target = $this->service->findByUuid($uuid);
        return $this->successResponseWithData(
            data: $this->targetViewModelMapper->fromEntity($target)->toArray()
        );
    }
}
