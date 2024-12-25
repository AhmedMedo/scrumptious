<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Profile;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Infrastructure\Http\Request\ChangePasswordRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Patch(
    path: '/api/v1/auth/change-password',
    requestBody: new OA\RequestBody('#/components/requestBodies/ChangePasswordRequest'),
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
class ChangePasswordHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
    ) {
    }

    public function __invoke(ChangePasswordRequest $request): JsonResponse
    {
        $this->userService->updatePassword(
            $this->userService->user()->uuid(),
            $request->oldPassword(),
            $request->password()
        );

        return $this->successResponseWithMessage('Password Changed Successfully');
    }
}
