<?php

namespace App\Components\Notification\Infrastructure\Http\Handler;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Patch(
    path: '/notifications/read-all',
    description: 'Mark all notifications as read',
    summary: 'Mark all as read',
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
                ]
            )
        ),
    ]
)]
class MarkAllAsReadHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly NotificationServiceInterface $notificationService
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $user = $this->userService->user();
        $success = $this->notificationService->markAllAsRead($user->uuid());

        if (!$success) {
            return $this->errorResponse('Failed to mark all notifications as read', 400);
        }

        return $this->successResponseWithMessage('All notifications marked as read');
    }
}
