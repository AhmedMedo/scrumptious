<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Mapper\UserDtoMapperInterface;
use App\Components\Auth\Application\Mapper\UserViewModelMapperInterface;
use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Domain\Exception\AccountInactiveException;
use App\Components\Auth\Domain\Exception\UserNotFoundException;
use App\Components\Auth\Domain\Exception\WrongCredentialsException;
use App\Components\Auth\Infrastructure\Http\Request\GuestLoginRequest;
use App\Components\Auth\Infrastructure\Http\Request\LoginRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/v1/auth/login-as-guest',
    requestBody: new OA\RequestBody('#/components/requestBodies/GuestLoginRequest'),
    tags: ['Auth'],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'uuid', type: 'string'),
                    new OA\Property(property: 'first_name', type: 'string'),
                    new OA\Property(property: 'last_name', type: 'string'),
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'phone_number', type: 'string'),
                    new OA\Property(property: 'is_active', type: 'boolean'),
                    new OA\Property(property: 'created_at', type: 'string'),
                    new OA\Property(property: 'updated_at', type: 'string'),
                    new OA\Property(property: 'meta', properties: [
                        new OA\Property(property: 'token', type: 'string'),
                        new OA\Property(property: 'token_type', type: 'string'),
                        new OA\Property(property: 'expires_at', type: 'string'),
                    ]),
                    new OA\Property(property: 'cart', properties: [
                        new OA\Property(property: 'count', type: 'integer'),
                    ]),

                ]),
            ],
            type: 'object'
        )),
    ]

)]
class GuestLoginHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,

    ) {
    }


    /**
     * @param GuestLoginRequest $request
     *
     * @return JsonResponse
     **/
    public function __invoke(GuestLoginRequest $request): JsonResponse
    {
        $userVerificationDto = $this->userService->loginAsGuest($request->name(), $request->email(), $request->phone());
        if (is_bool($userVerificationDto) && !$userVerificationDto) {
            return $this->errorResponse('User already exists. Please proceed to log in.');
        }
        return $this->successResponseWithData([
            'token' => $userVerificationDto->token(),
        ]);

    }
}
