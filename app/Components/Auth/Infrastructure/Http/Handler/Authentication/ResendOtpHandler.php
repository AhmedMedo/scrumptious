<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Infrastructure\Http\Request\ResendOtpRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Post(
    path       : '/api/v1/auth/resend-otp',
    requestBody: new OA\RequestBody('#/components/requestBodies/ResendOtpRequest'),
    tags       : ['Auth'],
    responses  : [
        new OA\Response(response: 200, description: 'OTP has been sent successfully', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'object'),
            ],
            type      : 'object'
        )),
    ]
)]

class ResendOtpHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {
    }


    public function __invoke(ResendOtpRequest $request): JsonResponse
    {
        try {
            $this->userService->resendOtp(
                $request->token()
            );
        } catch (\Exception $exception) {
            return $this->errorResponseWithData([], $exception->getMessage());
        }
        return $this->successResponseWithMessage('OTP has been sent successfully');
    }
}
