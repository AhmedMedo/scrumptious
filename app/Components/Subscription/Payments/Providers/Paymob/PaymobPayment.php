<?php

namespace App\Components\Subscription\Payments\Providers\Paymob;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Subscription\Data\Entity\SubscriptionPlanEntity;
use MG\Paymob\Paymob;

class PaymobPayment
{
    public function createPayment(UserEntity $user, SubscriptionPlanEntity $plan, int $merchantOrderId): array
    {
        $paymob = new Paymob();

        $amountCents = (int)($plan->price * 100);

        $orderItems = [[
            'name' => $plan->name,
            'amount_cents' => $amountCents,
            'description' => $plan->description ?? 'Subscription plan',
            'quantity' => 1,
        ]];

        $billingData = [
            'first_name'      => $user->first_name ?? 'N/A',
            'last_name'       => $user->last_name ?? 'N/A',
            'email'           => $user->email,
            'phone_number'    => $user->phone_number ?? 'N/A',
            'apartment'       => 'N/A',
            'floor'           => 'N/A',
            'building'        => 'N/A',
            'street'          => 'N/A',
            'city'            => 'N/A',
            'shipping_method' => 'N/A',
            'country'         => 'N/A',
            'state'           => 'N/A',
        ];

        $auth = $paymob->auth();
        $token = $auth['token'];

        $order = $paymob->makeOrder($token, false, $amountCents, $orderItems, $merchantOrderId);
        $orderId = $order['id'];

        $paymentKey = $paymob->getPaymentKey($token, $amountCents, 3600, $orderId, $billingData, $plan->currency ?? 'EGP');

        $redirectUrl = 'https://accept.paymobsolutions.com/api/acceptance/iframes/' .
            config('paymob.auth.iframe_id') . '?payment_token=' . $paymentKey['token'];

        return [
            'redirect_url' => $redirectUrl,
            'order_id' => $orderId,
        ];
    }
}
