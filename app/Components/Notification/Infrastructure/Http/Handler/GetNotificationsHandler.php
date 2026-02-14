<?php

namespace App\Components\Notification\Infrastructure\Http\Handler;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Notification\Application\Query\NotificationQueryInterface;
use App\Components\Notification\Data\Entity\NotificationEntity;
use App\Components\Notification\Infrastructure\Http\Request\GetNotificationsRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/notifications',
    description: 'Get user notifications',
    summary: 'Get notifications',
    tags: ['Notifications'],
    parameters: [
        new OA\Parameter(name: 'Authorization', description: 'Bearer Token', in: 'header', required: true, schema: new OA\Schema(type: 'string')),
        new OA\Parameter(name: 'per_page', description: 'Items per page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        new OA\Parameter(name: 'unread_only', description: 'Get only unread notifications', in: 'query', required: false, schema: new OA\Schema(type: 'boolean')),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Success',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string'),
                    new OA\Property(property: 'message', type: 'string'),
                    new OA\Property(property: 'data', type: 'array', items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'uuid', type: 'string'),
                            new OA\Property(property: 'type', type: 'string'),
                            new OA\Property(property: 'title', type: 'string'),
                            new OA\Property(property: 'body', type: 'string'),
                            new OA\Property(property: 'data', type: 'object'),
                            new OA\Property(property: 'is_read', type: 'boolean'),
                            new OA\Property(property: 'created_at', type: 'string'),
                        ]
                    )),
                    new OA\Property(property: 'meta', ref: '#/components/schemas/Meta', type: 'object'),
                ]
            )
        ),
    ]
)]
class GetNotificationsHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly NotificationQueryInterface $notificationQuery
    ) {
    }

    public function __invoke(GetNotificationsRequest $request): JsonResponse
    {
        $user = $this->userService->user();
        $unreadOnly = $request->boolean('unread_only', false);

        $notifications = $unreadOnly
            ? $this->notificationQuery->getUnreadByUserUuid($user->uuid())
            : $this->notificationQuery->getNotificationsByUserUuid($user->uuid());

        return $this->successResponseWithDataAndMeta(
            data: $notifications->map(function (NotificationEntity $entity) {
                return [
                    'uuid' => $entity->uuid,
                    'type' => $entity->type->value,
                    'title' => $entity->title,
                    'body' => $entity->body,
                    'data' => $entity->data,
                    'is_read' => $entity->is_read,
                    'read_at' => $entity->read_at?->toIso8601String(),
                    'created_at' => $entity->created_at->toIso8601String(),
                ];
            })->toArray(),
            meta: [
                'total' => $notifications->total(),
                'per_page' => $notifications->perPage(),
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'from' => $notifications->firstItem(),
                'to' => $notifications->lastItem(),
            ]
        );
    }
}
