<?php

namespace App\Components\Notification\Infrastructure\Http\Handler;

use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Delete(
    path: '/notifications/{uuid}',
    description: 'Delete notification',
    summary: 'Delete notification',
    tags: ['Notifications'],
    parameters: [
        new OA\Parameter(name: 'Authorization', description: 'Bearer Token', in: 'header', required: true, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'uuid', description: 'Notification UUID', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
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
class DeleteNotificationHandler extends Handler
{
    public function __construct(
        private readonly NotificationServiceInterface $notificationService
    ) {
    }

    public function __invoke(string $uuid): JsonResponse
    {
        $success = $this->notificationService->deleteNotification($uuid);

        if (!$success) {
            return $this->errorResponse('Failed to delete notification', 400);
        }

        return $this->successResponseWithMessage('Notification deleted successfully');
    }
}
