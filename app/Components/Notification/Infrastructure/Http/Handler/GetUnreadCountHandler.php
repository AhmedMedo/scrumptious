<?php

namespace App\Components\Notification\Infrastructure\Http\Handler;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/notifications/unread-count',
    description: 'Get unread notifications count',
    summary: 'Get unread count',
    tags: ['Notifications'],
    parameters: [
        new OA\Parameter(name: 'Authorization', description: 'Bearer Token', in: 'header', required: true, schema: new OA\Schema(type: 'string')),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Success',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string'),
                    new OA\Property(property: 'message', type: 'string'),
                    new OA\Property(property: 'data', type: 'object', properties: [
                        new OA\Property(property: 'unread_count', type: 'integer'),
                    ]),
                ]
            )
        ),
    ]
)]
class GetUnreadCountHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly NotificationServiceInterface $notificationService
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $user = $this->userService->user();
        $count = $this->notificationService->getUnreadCount($user->uuid());

        return $this->successResponseWithData([
            'unread_count' => $count,
        ]);
    }
}
