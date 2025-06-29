<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Components\Subscription\Data\Entity\PaymobPaymentEntity;
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
        $merchantOrderId = $request->input('merchant_order_id');
        $payment = PaymobPaymentEntity::query()->where('merchant_order_id', $merchantOrderId)->first();

        if ($payment) {
            $payment->update([
                'payment_id' => $request->input('id'),
                'order_id' => $request->input('order'),
                'status' => $request->boolean('success') ? 'paid' : 'failed',
                'response' => $request->all(),
            ]);
        }

        return redirect( $request->boolean('success') ?'myapp://payment-success': 'myapp://payment-failed');

    }
}
