<?php

namespace App\Components\Auth\Infrastructure\Http\Handler\Authentication;

use App\Components\Auth\Application\Mapper\UserViewModelMapperInterface;
use App\Components\Auth\Application\Service\UserServiceInterface;
use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\MealPlanner\Data\Entity\PlanEntity;
use App\Components\Subscription\Data\Entity\PaymobPaymentEntity;
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
        private readonly UserServiceInterface $userService,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        try {
            $userEntity = UserEntity::query()->find($this->userService->user()->uuid());
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

            $extra['plan'] = $planData;
            $extra['payment'] = $paymentData;
            return $this->successResponseWithData(array_merge($userEntity->toArray(), $extra));
        } catch (\Exception $exception) {
            return $this->errorResponseWithData([], $exception->getMessage());
        }
    }
}
