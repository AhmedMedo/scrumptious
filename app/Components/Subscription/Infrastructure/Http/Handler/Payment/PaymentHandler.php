<?php

namespace App\Components\Subscription\Infrastructure\Http\Handler\Payment;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Subscription\Data\Entity\SubscriptionPlanEntity;
use App\Components\Subscription\Data\Entity\PaymobPaymentEntity;
use App\Components\Subscription\Payments\Providers\Paymob\PaymobPayment;
use App\Components\Subscription\PaymobClass;
use App\Libraries\Base\Http\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/api/v1/subscription/payment',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['subscription_plan_uuid'],
            properties: [
                new OA\Property(property: 'subscription_plan_uuid', type: 'string'),
            ]
        )
    ),
    tags: ['Subscription'],
    responses: [
        new OA\Response(
            response: 200,
            description: 'redirect url',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'status', type: 'string'),
                    new OA\Property(property: 'message', type: 'string'),
                    new OA\Property(property: 'data', properties: [
                        new OA\Property(property: 'redirect_url', type: 'string'),
                    ])
                ]
            )
        )
    ]
)]
class PaymentHandler extends Handler
{
    public function __construct(private readonly PaymobPayment $paymob)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subscription_plan_uuid' => 'required|exists:subscription_plans,uuid',
        ]);

        $user = auth()->user();
        $userEntity = UserEntity::query()->findOrFail($user->uuid);
        $plan = SubscriptionPlanEntity::query()->findOrFail($validated['subscription_plan_uuid']);
        $merchantOrderId = random_int(1, 999999);

        $paymentInfo = $this->paymob->createPayment($userEntity, $plan, $merchantOrderId);

        PaymobPaymentEntity::create([
            'user_uuid' => $user->uuid,
            'subscription_plan_uuid' => $plan->uuid,
            'amount' => $plan->price,
            'status' => 'pending',
            'order_id' => $paymentInfo['order_id'],
            'merchant_order_id' => $merchantOrderId,
        ]);

        return $this->successResponseWithData([
            'order_id' => $paymentInfo['order_id'],
            'redirect_url' => $paymentInfo['redirect_url'],
        ]);
    }
}
