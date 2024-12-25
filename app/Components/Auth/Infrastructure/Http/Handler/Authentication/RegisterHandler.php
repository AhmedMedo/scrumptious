<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Infrastructure\Http\Request\RegisterRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Post(
    path       : '/api/v1/auth/register',
    requestBody: new OA\RequestBody('#/components/requestBodies/RegisterRequest'),
    tags       : ['Auth'],
    responses  : [
        new OA\Response(response: 200, description: 'User registered successfully ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'token', type: 'string'),
                ]),
            ],
            type      : 'object'
        )),
    ]
)]
class RegisterHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
    ) {
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $userVerificationDto = $this->userService->register($request->validated());
        return $this->successResponseWithData([
            'token' => $userVerificationDto->token(),
        ]);
    }
}
