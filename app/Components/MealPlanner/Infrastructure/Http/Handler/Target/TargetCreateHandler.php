<?php

namespace App\Components\MealPlanner\Infrastructure\Http\Handler\Target;
use App\Components\Auth\Infrastructure\Service\UserService;
use App\Components\MealPlanner\Application\Service\Target\TargetServiceInterface;
use App\Components\MealPlanner\Infrastructure\Http\Request\Target\CreateTargetRequest;
use App\Libraries\Base\Http\Handler;
use OpenApi\Attributes as OA;
#[OA\Post(
    path: '/api/v1/target',
    description: 'Create new Target',
    summary: 'Create new Target',
    requestBody: new OA\RequestBody('#/components/requestBodies/CreateTargetRequest'),
    tags: ['Target'],
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
class TargetCreateHandler extends Handler
{


    public function __construct(
        private readonly TargetServiceInterface $targetService,
        private readonly UserService $userService,
    )
    {
    }

    public function __invoke(CreateTargetRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->userService->user();
        $target = $this->targetService->store(array_merge($request->validated(),['user_uuid' => $user->uuid()]));
        return $this->successResponseWithMessage('Target created successfully');
    }
}
