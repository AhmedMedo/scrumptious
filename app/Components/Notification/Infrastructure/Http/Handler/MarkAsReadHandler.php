<?php

namespace App\Components\Notification\Infrastructure\Http\Handler;

use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Components\Notification\Infrastructure\Http\Request\MarkAsReadRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Patch(
    path: '/notifications/{uuid}/read',
    description: 'Mark notification as read',
    summary: 'Mark as read',
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
class MarkAsReadHandler extends Handler
{
    public function __construct(
        private readonly NotificationServiceInterface $notificationService
    ) {
    }

    public function __invoke(string $uuid): JsonResponse
    {
        $success = $this->notificationService->markAsRead($uuid);

        if (!$success) {
            return $this->errorResponse('Failed to mark notification as read', 400);
        }

        return $this->successResponseWithMessage('Notification marked as read');
    }
}
