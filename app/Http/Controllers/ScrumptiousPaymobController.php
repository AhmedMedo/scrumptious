<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
    public function response(Request $request): JsonResponse
    {
        return response()->json($request->all());

    }
}
