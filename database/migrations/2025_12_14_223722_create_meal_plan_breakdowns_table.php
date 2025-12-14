<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meal_plan_breakdowns', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('plan_uuid');
            $table->date('date');
            $table->timestamps();

            $table->foreign('plan_uuid')->references('uuid')->on('plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meal_plan_breakdowns', function (Blueprint $table) {
            $table->dropForeign(['plan_uuid']);
        });
        Schema::dropIfExists('meal_plan_breakdowns');
    }
};
