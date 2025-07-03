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
        if(is_string($payload)){
            $payload = json_decode($payload, true);
        }

        // Support both flat and nested webhook formats
        $merchantOrderId = $payload['merchant_order_id'] ?? ($payload['obj']['order']['merchant_order_id'] ?? null);
        $orderId = $payload['order'] ?? ($payload['obj']['order']['id'] ?? null);
        $paymentId = $payload['id'] ?? ($payload['obj']['id'] ?? null);

        // 'success' can be string or bool
        $success = $payload['success'] ?? ($payload['obj']['success'] ?? false);
        $success = filter_var($success, FILTER_VALIDATE_BOOLEAN);

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
