<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paymob_payments', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('user_uuid')->index();
            $table->uuid('subscription_plan_uuid')->index();
            $table->integer('amount')->default(0);
            $table->string('status')->default('pending');
            $table->string('payment_id')->nullable();
            $table->string('order_id')->nullable();
            $table->string('merchant_order_id')->nullable();
            $table->json('response')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paymob_payments');
    }
};
