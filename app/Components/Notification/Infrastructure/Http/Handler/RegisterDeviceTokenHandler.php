<?php

namespace App\Components\Notification\Infrastructure\Http\Handler;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Notification\Application\Repository\UserDeviceTokenRepositoryInterface;
use App\Components\Notification\Data\Enums\DeviceTypeEnum;
use App\Components\Notification\Infrastructure\Http\Request\RegisterDeviceTokenRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/notifications/register-device',
    description: 'Register device token for push notifications',
    summary: 'Register device token',
    tags: ['Notifications'],
    parameters: [
        new OA\Parameter(name: 'Authorization', description: 'Bearer Token', in: 'header', required: true, schema: new OA\Schema(type: 'string')),
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['device_token', 'device_type'],
            properties: [
                new OA\Property(property: 'device_token', type: 'string'),
                new OA\Property(property: 'device_type', type: 'string', enum: ['ios', 'android', 'web']),
                new OA\Property(property: 'device_name', type: 'string', nullable: true),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Success',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string'),
                    new OA\Property(property: 'message', type: 'string'),
                    new OA\Property(property: 'data', type: 'object', properties: [
                        new OA\Property(property: 'uuid', type: 'string'),
                        new OA\Property(property: 'device_type', type: 'string'),
                        new OA\Property(property: 'is_active', type: 'boolean'),
                    ]),
                ]
            )
        ),
    ]
)]
class RegisterDeviceTokenHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly UserDeviceTokenRepositoryInterface $deviceTokenRepository
    ) {
    }

    public function __invoke(RegisterDeviceTokenRequest $request): JsonResponse
    {
        $user = $this->userService->user();

        $deviceToken = $this->deviceTokenRepository->registerToken(
            $user->uuid(),
            $request->input('device_token'),
            DeviceTypeEnum::from($request->input('device_type')),
            $request->input('device_name')
        );

        return $this->successResponseWithData([
            'uuid' => $deviceToken->uuid,
            'device_type' => $deviceToken->device_type->value,
            'device_name' => $deviceToken->device_name,
            'is_active' => $deviceToken->is_active,
        ], 'Device token registered successfully');
    }
}
