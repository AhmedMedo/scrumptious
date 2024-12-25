<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Infrastructure\Http\Request\ResetPasswordRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Post(
    path       : '/api/v1/auth/reset-password',
    requestBody: new OA\RequestBody('#/components/requestBodies/ResetPasswordRequest'),
    tags       : ['Auth'],
    responses  : [
        new OA\Response(response: 200, description: 'Password has been reset successfully', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'object'),
            ],
            type      : 'object'
        )),
    ]
)]

class ResetPasswordHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {
    }


    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->userService->resetPassword(
                $request->input('token'),
                $request->input('password'),
            );
        } catch (\Exception $exception) {
            return  $this->errorResponse($exception->getMessage());
        }

        return $this->successResponseWithMessage('Password is changed successfully.');
    }
}
