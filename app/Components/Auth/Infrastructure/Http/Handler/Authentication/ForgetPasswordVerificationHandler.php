<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Service\UserVerificationServiceInterface;
use App\Components\Auth\Infrastructure\Http\Request\UserVerificationRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/v1/auth/forget-password-verification',
    requestBody: new OA\RequestBody('#/components/requestBodies/UserVerificationRequest'),
    tags: ['Auth'],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'object'),

            ],
            type: 'object'
        )),
    ]
)]
class ForgetPasswordVerificationHandler extends Handler
{
    public function __construct(
        private readonly UserVerificationServiceInterface $userVerificationService,
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
             $this->userVerificationService->verifyOtp(
                 $request->get('token'),
                 $request->get('otp')
             );
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }

        return $this->successResponseWithMessage('User verified successfully');
    }
}
