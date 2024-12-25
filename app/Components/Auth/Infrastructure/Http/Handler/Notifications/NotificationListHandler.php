<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Notifications;

use App\Components\Auth\Application\Query\NotificationQueryInterface;
use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Data\Entity\NotificationEntity;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/auth/notifications',
    description: 'Get notifications',
    summary: 'Get notifications',
    tags: ['Auth'],
    parameters: [
        new OA\Parameter(name:'Authorization', description: 'Bearer Token', required: true, schema: new OA\Schema(type: 'string')),
    ],
    responses: [
        new OA\Response(response: 200, description: 'Success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(properties: [
                    new OA\Property(property: 'uuid', type: 'string'),
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'description', type: 'string'),
                ])),
                new OA\Property(property: 'meta', ref: '#/components/schemas/Meta', type: 'object'),
            ]
        )),
    ]
)]

class NotificationListHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly NotificationQueryInterface $notificationQuery
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $user = $this->userService->user();
        $notifications = $this->notificationQuery->getNotificationsByUserUuid($user->uuid());
        return $this->successResponseWithDataAndMeta(
            data: $notifications->map(function (NotificationEntity $entity) {
                return [
                    'uuid' => $entity->getKey(),
                    'title' => $entity->title,
                    'description' => $entity->description,
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
