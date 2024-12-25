<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Profile;

use App\Components\Auth\Application\Mapper\UserViewModelMapperInterface;
use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Infrastructure\Http\Request\UpdateProfileRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Patch(
    path: '/api/v1/auth/update-profile',
    requestBody: new OA\RequestBody('#/components/requestBodies/UpdateProfileRequest'),
    tags: ['Auth'],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', ref: '#/components/schemas/UserViewModel'),
            ],
            type: 'object'
        )),
    ]
)]

class UpdateProfileHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly UserViewModelMapperInterface $userViewModelMapper,
    ) {
    }

    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->userService->updateProfile(
            $this->userService->user()->uuid(),
            $request->toArray(),
            $request->imagePath()
        );

        return $this->successResponseWithData(
            $this->userViewModelMapper->map(
                $user
            )->toArray(),
        );
    }
}
