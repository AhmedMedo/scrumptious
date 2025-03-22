<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Handler\Target;
use App\Components\Auth\Infrastructure\Service\UserService;
use App\Components\MealPlanner\Application\Mapper\Target\TargetViewModelMapper;
use App\Components\MealPlanner\Application\Service\Target\TargetServiceInterface;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/target/list',
    summary: 'List of targets',
    tags: ['Target'],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/TargetViewModel', type: 'object')),
                new OA\Property(property: 'meta', ref: '#/components/schemas/Meta'),
            ],
            type      : 'object'
        )),
    ]
)]
class TargetListHandler extends Handler
{
    public function __construct(
        private readonly TargetServiceInterface $service,
        private readonly TargetViewModelMapper $targetViewModelMapper,
        private readonly UserService $userService
    )
    {
    }

    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $user = $this->userService->user();
        $targets = $this->service->paginated($user->uuid());
        return $this->successResponseWithDataAndMeta(
            data: $targets->map(fn ($target) => $this->targetViewModelMapper->fromEntity($target)->toArray())->toArray(),
            meta: [
                'total' => $targets->total(),
                'per_page' => $targets->perPage(),
                'current_page' => $targets->currentPage(),
                'last_page' => $targets->lastPage(),
            ]
        );
    }
}
