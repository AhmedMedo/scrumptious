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
        // Handle both raw JSON and form-data
        $raw = $request->getContent();
        $data = $request->all();
        Log::debug('all', ['raw' => $raw, 'data' => $data]);
        // If the request is a string (raw JSON), decode it
//        if (is_string($raw) && !empty($raw)) {
//            $data = json_decode($raw, true);
//        }

        // Always extract the actual payload
        $payload = $data['payload'] ?? [];

        Log::debug('Paymob Callback Response', ['payload' => $payload]);

        if (empty($payload)) {
            Log::debug('Paymob Callback Response Empty');
            return redirect('scrumptious://payment-failed');
        }

        $merchantOrderId = $payload['merchant_order_id'] ?? null;
        $orderId = $payload['order'] ?? null;
        $paymentId = $payload['id'] ?? null;
        $success = $payload['success'] ?? false;
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
