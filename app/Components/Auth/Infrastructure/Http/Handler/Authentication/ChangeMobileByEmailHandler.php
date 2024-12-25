<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Infrastructure\Http\Request\ChangeMobileByEmailRequest;
use App\Libraries\Base\Database\ConnectionService;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/v1/auth/change-mobile-by-email',
    requestBody: new OA\RequestBody('#/components/requestBodies/ChangeMobileByEmailRequest'),
    tags: ['Auth'],
    responses  : [
        new OA\Response(response: 200, description: 'Email has been resend', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'object'),
            ],
            type      : 'object'
        )),
    ]
)]
class ChangeMobileByEmailHandler extends Handler
{



    public function __construct(
        private readonly UserServiceInterface $service,
        private readonly ConnectionService $connection
    )
    {
    }


    /**
     * @param ChangeMobileByEmailRequest $request
     * @return JsonResponse
     */
    public function __invoke(ChangeMobileByEmailRequest $request): JsonResponse
    {
        try {
            $this->connection->beginTransaction();
            $userVerificationDto = $this->service->changeUserPhoneByEmail($request->email(), $request->countryCode(), $request->phoneNumber());
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
