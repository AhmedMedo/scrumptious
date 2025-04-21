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
        Schema::create('meal_recipe', function (Blueprint $table) {
            $table->uuid('meal_uuid');
            $table->uuid('recipe_uuid');

            $table->foreign('meal_uuid')->references('uuid')->on('meals')->onDelete('cascade');
            $table->foreign('recipe_uuid')->references('uuid')->on('recipes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meal_recipe', function (Blueprint $table) {
            $table->dropForeign(['meal_uuid']);
            $table->dropForeign(['recipe_uuid']);
        });
        Schema::dropIfExists('meal_recipe');
    }
};
