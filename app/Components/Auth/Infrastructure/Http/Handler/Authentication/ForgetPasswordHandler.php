<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Domain\Exception\UserNotFoundException;
use App\Components\Auth\Infrastructure\Http\Request\ForgetPasswordRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/v1/auth/forget-password',
    requestBody: new OA\RequestBody('#/components/requestBodies/ForgetPasswordRequest'),
    tags: ['Auth'],
    responses: [
        new OA\Response(response: 200, description: 'Forget Password code sent successfully', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'token', type: 'string'),
                ]),
            ],
            type: 'object'
        )),
    ]
)]
class ForgetPasswordHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {
    }


    public function __invoke(ForgetPasswordRequest $request): JsonResponse
    {
        try {
            $userVerificationDto = $this->userService->forgetPassword(
                $request->input('username'),
            );
        } catch (UserNotFoundException $exception) {
            throw ValidationException::withMessages([
                'email' => [$exception->getMessage()],
            ]);
        }

        return $this->successResponseWithData([
            'token' => $userVerificationDto->token(),
        ]);
    }
}
