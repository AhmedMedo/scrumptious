<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Mapper\UserDtoMapperInterface;
use App\Components\Auth\Application\Mapper\UserViewModelMapperInterface;
use App\Components\Auth\Application\Service\UserVerificationServiceInterface;
use App\Components\Auth\Infrastructure\Http\Request\EmailVerificationRequest;
use App\Components\Auth\Infrastructure\Http\Request\UserVerificationRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/v1/auth/verify-email',
    requestBody: new OA\RequestBody('#/components/requestBodies/EmailVerificationRequest'),
    tags: ['Auth'],
    responses  : [
        new OA\Response(response: 200, description: 'Email has been verified', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'object'),
            ],
            type      : 'object'
        )),
    ]
)]
class EmailVerificationHandler extends Handler
{
    public function __construct(
        private readonly UserVerificationServiceInterface $userVerificationService,
    ) {
    }

    /**
     * @param EmailVerificationRequest $request
     * @return JsonResponse
     */
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        try {
            $this->userVerificationService->verifyEmail($request->token());
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }



        return $this->successResponseWithMessage('Email has been verified');
    }
}
