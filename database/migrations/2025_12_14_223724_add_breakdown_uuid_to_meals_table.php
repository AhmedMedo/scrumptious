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
        Schema::table('meals', function (Blueprint $table) {
            $table->uuid('breakdown_uuid')->nullable()->after('plan_uuid');
            $table->foreign('breakdown_uuid')->references('uuid')->on('meal_plan_breakdowns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropForeign(['breakdown_uuid']);
            $table->dropColumn('breakdown_uuid');
        });
    }
};
