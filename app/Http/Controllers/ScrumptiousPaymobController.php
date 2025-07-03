<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Components\Subscription\Data\Entity\PaymobPaymentEntity;
use Illuminate\Support\Facades\Log;
use MG\Paymob\Controllers\PaymobController;

class ScrumptiousPaymobController extends Controller
{
    public function processed(Request $request): JsonResponse
    {
        return response()->json($request->all());

    }


    /**
     * Handle Transaction Callback Response.
     */
    public function response(Request $request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    {
        $payload = $request->all();
        Log::debug('Paymob Callback Response', ['payload' => $payload]);
        // Handle both JSON and form-data
        if (empty($payload) && str_contains($request->header('Content-Type'), 'json')) {
            $payload = json_decode($request->getContent(), true);
        }

        // Paymob sends the data in 'obj' for transaction webhooks
        $obj = $payload['obj'] ?? null;
        if (!$obj) {
            // fallback for legacy or malformed payloads
            $obj = $payload;
        }

        // Extract merchant_order_id (deep in obj['order']['merchant_order_id'])
        $merchantOrderId = $obj['order']['merchant_order_id'] ?? null;
        $orderId = $obj['order']['id'] ?? null;
        $paymentId = $obj['id'] ?? null;
        $success = $obj['success'] ?? false;
        $status = $success ? 'paid' : 'failed';

        $payment = $merchantOrderId ? PaymobPaymentEntity::query()->where('merchant_order_id', $merchantOrderId)->first() : null;

        if ($payment) {
            $payment->update([
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'status' => $status,
                'response' => $payload,
            ]);
        }

        return redirect($success ? 'scrumptious://payment-success' : 'scrumptious://payment-failed');
    }
}
