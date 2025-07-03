<?php

namespace App\Components\Subscription\Payments\Providers\Paymob;

use App\Components\Auth\Data\Entity\UserEntity;
use App\Components\Subscription\Data\Entity\SubscriptionPlanEntity;
use MG\Paymob\Paymob;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\Facades\Log;

class PaymobPayment
{
    public function createPayment(UserEntity $user, SubscriptionPlanEntity $plan, string $merchantOrderId): array
    {
        $client = new HttpClient();
        $paymobSecretKey = config('paymob.paymob_secret_key');
        $paymobIntegrationId = config('paymob.paymob_integration_id');
        $webhookUrl = route('paymob.response');
//        $webhookUrl='https://webhook.site/f29538c7-42ba-49d8-847d-a241079b5cd8';
        $redirectUrl = route('home');

        $amountCents = (int)($plan->price * 100);

        $payload = [
            'amount' => $amountCents,
            'currency' => $plan->currency ?? 'EGP',
            'payment_methods' => [(int)$paymobIntegrationId],
            'items' => [[
                'name' => $plan->name,
                'amount' => $amountCents,
                'description' => $plan->description ?? 'Subscription plan',
                'quantity' => 1,
            ]],
            'billing_data' => [
                'apartment' => 'N/A',
                'first_name' => $user->first_name ?? 'N/A',
                'last_name' => $user->last_name ?? 'N/A',
                'street' => 'N/A',
                'building' => 'N/A',
                'phone_number' => $user->phone_number ?? 'N/A',
                'city' => 'N/A',
                'country' => 'N/A',
                'email' => $user->email,
                'floor' => 'N/A',
                'state' => 'N/A',
            ],
            'extras' => [
                'ee' => 22
            ],
            'special_reference' => $merchantOrderId,
            'notification_url' => $webhookUrl,
            'redirection_url' => $redirectUrl,
        ];

        $headers = [
            'Authorization' => 'Token ' . $paymobSecretKey,
            'Content-Type' => 'application/json',
        ];

        Log::info('Paymob Intention Request', ['payload' => $payload]);

        try {
            $response = $client->post('https://accept.paymob.com/v1/intention/?=null', [
                'headers' => $headers,
                'json' => $payload,
                'http_errors' => false,
            ]);
            $body = json_decode($response->getBody()->getContents(), true);
            Log::info('Paymob Intention Response', ['response' => $body]);

            if ($response->getStatusCode() !== 201 || !isset($body['client_secret'])) {
                Log::error('Paymob Intention Error', ['response' => $body]);
                throw new \Exception('Failed to create payment intention: ' . ($body['message'] ?? 'Unknown error'));
            }
            $publicKey = config('paymob.paymob_public_key');
            $clientSecret = $body['client_secret'];
            return [
                'client_secret' => $body['client_secret'] ?? null,
                'order_id' => $body['intention_order_id'] ?? null,
                'redirect_url' => sprintf('https://accept.paymob.com/unifiedcheckout/?publicKey=%s&clientSecret=%s', $publicKey, $clientSecret),
                'raw_response' => $body,
            ];
        } catch (\Exception $e) {
            Log::error('Paymob Intention Error', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
