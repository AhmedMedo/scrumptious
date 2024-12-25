<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Mapper\UserViewModelMapperInterface;
use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/auth/profile',
    tags: ['Auth'],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'uuid', type: 'string'),
                    new OA\Property(property: 'first_name', type: 'string'),
                    new OA\Property(property: 'last_name', type: 'string'),
                    new OA\Property(property: 'email', type: 'string'),
                    new OA\Property(property: 'phone_number', type: 'string'),
                    new OA\Property(property: 'is_active', type: 'boolean'),
                    new OA\Property(property: 'created_at', type: 'string'),
                    new OA\Property(property: 'updated_at', type: 'string'),
                ]),
            ],
            type: 'object'
        )),
    ]
)]
class ProfileHandler extends Handler
{
    public function __construct(
        private readonly UserViewModelMapperInterface $userViewModelMapper,
        private readonly UserServiceInterface $userService
    ) {
    }

    public function __invoke(): JsonResponse
    {
        try {
            return $this->successResponseWithData($this->userViewModelMapper->map(
                $this->userService->user()
            )->toArray());
        } catch (\Exception $exception) {
            return $this->errorResponseWithData([], $exception->getMessage());
        }
    }
}
