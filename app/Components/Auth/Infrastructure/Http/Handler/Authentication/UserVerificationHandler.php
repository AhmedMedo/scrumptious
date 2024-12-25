<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Mapper\UserDtoMapperInterface;
use App\Components\Auth\Application\Mapper\UserViewModelMapperInterface;
use App\Components\Auth\Application\Service\UserVerificationServiceInterface;
use App\Components\Auth\Data\Enums\RoleEnum;
use App\Components\Auth\Infrastructure\Http\Request\UserVerificationRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/v1/auth/verification',
    requestBody: new OA\RequestBody('#/components/requestBodies/UserVerificationRequest'),
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
class UserVerificationHandler extends Handler
{
    public function __construct(
        private readonly UserVerificationServiceInterface $userVerificationService,
        private readonly UserDtoMapperInterface $userDtoMapper,
        private readonly UserViewModelMapperInterface $userViewModelMapper
    ) {
    }

    /**
     * @param UserVerificationRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function __invoke(UserVerificationRequest $request): JsonResponse
    {
        try {
            $userEntity = $this->userVerificationService->verifyOtp(
                $request->get('token'),
                $request->get('otp')
            );
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }

        if (in_array($request->get('is_change_mobile'),[true,'true',1,'1']) )
        {
            return $this->successResponseWithMessage('Phone number changed successfully');
        }

        $accessToken = $userEntity->createToken('auth_token');

        return $this->successResponseWithData(
            array_merge(
                $this->userViewModelMapper->map(
                    $this->userDtoMapper->map($userEntity)
                )->toArray(),
                [
                    'meta' => [
                        'token' => $accessToken->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => $accessToken->token->expires_at->toDateTimeString(),
                        'is_guest' => $userEntity->hasRole(RoleEnum::GUEST->value) ? 1 : 0,
                    ],
                ]
            )
        );
    }
}
