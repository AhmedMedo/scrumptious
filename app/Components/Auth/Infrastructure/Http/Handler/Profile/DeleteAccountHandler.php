<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Profile;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Infrastructure\Http\Request\ChangePasswordRequest;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Delete(
    path: '/api/v1/auth/delete-account',
    tags: ['Auth'],
    responses: [
        new OA\Response(response: 200, description: 'Account Deleted Successfully ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string', example: 'Account Deleted Successfully'),
            ],
            type: 'object'
        )),
    ]
)]
class DeleteAccountHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->userService->deleteAccount($this->userService->user()->uuid());
        return $this->successResponseWithMessage('Account Deleted Successfully');
    }
}
