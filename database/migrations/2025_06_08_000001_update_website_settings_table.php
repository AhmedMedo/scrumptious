<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('website_settings');

        Schema::create('website_settings', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('website_name')->nullable();
            $table->longText('privacy_and_policy_en')->nullable();
            $table->longText('privacy_and_policy_ar')->nullable();
            $table->longText('terms_and_condition_en')->nullable();
            $table->longText('terms_and_condition_ar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
