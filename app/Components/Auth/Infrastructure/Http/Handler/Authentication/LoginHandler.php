<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Mapper\UserDtoMapperInterface;
use App\Components\Auth\Application\Mapper\UserViewModelMapperInterface;
use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Data\Enums\RoleEnum;
use App\Components\Auth\Domain\Exception\AccountInactiveException;
use App\Components\Auth\Domain\Exception\UserNotFoundException;
use App\Components\Auth\Domain\Exception\WrongCredentialsException;
use App\Components\Auth\Infrastructure\Http\Request\LoginRequest;
use App\Components\MealPlanner\Data\Entity\PlanEntity;
use App\Components\Subscription\Data\Entity\PaymobPaymentEntity;
use App\Helpers\TelegramLogger;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/v1/auth/login',
    requestBody: new OA\RequestBody('#/components/requestBodies/LoginRequest'),
    tags: ['Auth'],
    responses: [
        new OA\Response(response: 200, description: 'success ', content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string'),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'token', type: 'string'),
                ]),
            ],
            type: 'object'
        )),
    ]
)]
class LoginHandler extends Handler
{
    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly UserDtoMapperInterface $userDtoMapper,
        private readonly UserViewModelMapperInterface $userViewModelMapper

    ) {
    }


    /**
     * @param LoginRequest $request
     *
     * @return JsonResponse
     *
     * @throws ValidationException
     */
    public function __invoke(LoginRequest $request)
    {
        try {
            $userEntity = $this->userService->login($request->toArray());
        } catch (WrongCredentialsException $exception) {

            throw ValidationException::withMessages([
                'password' => [$exception->getMessage()],
            ]);
        } catch (AccountInactiveException | UserNotFoundException $exception) {
            throw ValidationException::withMessages([
                'email' => [$exception->getMessage()],
            ]);
        }
        $accessToken = $userEntity->createToken('auth_token');

        // Get user's current active plan
        $currentPlan = PlanEntity::query()
            ->where('user_uuid', $userEntity->uuid)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        $planData = null;
        if ($currentPlan) {
            $planData = [
                'uuid' => $currentPlan->uuid,
                'type' => $currentPlan->type,
                'start_date' => $currentPlan->start_date->format('Y-m-d'),
                'end_date' => $currentPlan->end_date->format('Y-m-d'),
                'status' => $currentPlan->status,
            ];
        }

        // Get user's latest payment status
        $latestPayment = PaymobPaymentEntity::query()
            ->where('user_uuid','=' , $userEntity->uuid)
            ->where('status', '=', 'paid')
            ->latest()
            ->first();

        $paymentData = null;
        if ($latestPayment) {
            $paymentData = [
                'status' => $latestPayment->status, // pending, paid, failed
                'amount' => $latestPayment->amount,
                'created_at' => $latestPayment->created_at->format('Y-m-d H:i:s'),
                'plan_name' => $latestPayment->plan->name ?? null,
            ];
        }

        return $this->successResponseWithData(
            array_merge(
                $this->userViewModelMapper->map(
                    $this->userDtoMapper->map($userEntity)
                )->toArray(),
                [
                    'meta' => [
                        'token' => $accessToken->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => $accessToken->token->expires_at->toDateTimeString(),
                        'is_guest' => $userEntity->hasRole(RoleEnum::GUEST->value) ? 1 : 0,
                    ],
                    'plan' => $planData,
                    'payment' => $paymentData,
                ]
            )
        );
    }
}
