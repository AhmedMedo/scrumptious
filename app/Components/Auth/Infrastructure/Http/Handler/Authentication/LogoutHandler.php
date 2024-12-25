<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Header(
    header: 'Authorization',
    description: 'Bearer Token',
    required: true,
    schema: new OA\Schema(type: 'string')
)]

#[OA\Get(
    path: '/api/v1/auth/logout',
    description: 'logout user',
    summary: 'logout',
    tags: ['Auth'],
    parameters: [
        new OA\Parameter(name:'Authorization', description: 'Bearer Token', required: true, schema: new OA\Schema(type: 'string')),
    ],
    responses: [
        new OA\Response(response: 200, description: 'Logout success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'string'),

            ]
        )),
    ]
)]
class LogoutHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $this->userService->logout();

        return $this->successResponseWithMessage('Logout success');
    }
}
