<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Profile;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Infrastructure\Http\Request\ChangeEmailRequest;
use App\Components\Auth\Infrastructure\Http\Request\ChangePasswordRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Patch(
    path: '/api/v1/auth/change-email',
    requestBody: new OA\RequestBody('#/components/requestBodies/ChangeEmailRequest'),
    tags: ['Auth'],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
            ],
            type: 'object'
        )),
    ]
)]
class ChangeEmailHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
    ) {
    }

    public function __invoke(ChangeEmailRequest $request): JsonResponse
    {
        $this->userService->changeEmail(
            $this->userService->user()->uuid(),
            $request->email(),
        );

        return $this->successResponseWithMessage('Email verification has been sent Successfully');
    }
}
