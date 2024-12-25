<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Profile;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Infrastructure\Http\Request\ChangeEmailRequest;
use App\Components\Auth\Infrastructure\Http\Request\ChangePasswordRequest;
use App\Components\Auth\Infrastructure\Http\Request\ChangePhoneRequest;
use App\Libraries\Base\Database\ConnectionService;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Patch(
    path: '/api/v1/auth/change-phone',
    requestBody: new OA\RequestBody('#/components/requestBodies/ChangePhoneRequest'),
    tags: ['Auth'],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
            ],
            type: 'object'
        )),
    ]
)]
class ChangPhoneHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly ConnectionService $connection

    ) {
    }

    public function __invoke(ChangePhoneRequest $request): JsonResponse
    {

        try {
            $this->connection->beginTransaction();
            $userVerificationDto = $this->userService->changePhone(
                $this->userService->user()->uuid(),
                $request->countryCode(),
                $request->phoneNumber()
            );
            $this->connection->commit();
            return $this->successResponseWithData([
                'token' => $userVerificationDto->token(),
            ]);
        } catch (\Exception $exception) {
            $this->connection->rollBack();
            return $this->errorResponse($exception->getMessage());
        }
    }
}
