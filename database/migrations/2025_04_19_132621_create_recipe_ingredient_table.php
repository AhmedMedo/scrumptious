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
        Schema::create('recipe_ingredient', function (Blueprint $table) {
            $table->uuid('recipe_uuid');
            $table->uuid('ingredient_uuid');

            $table->foreign('recipe_uuid')->references('uuid')->on('recipes');
            $table->foreign('ingredient_uuid')->references('uuid')->on('ingredients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipe_ingredient', function (Blueprint $table) {
            $table->dropForeign(['recipe_uuid']);
            $table->dropForeign(['ingredient_uuid']);
        });
        Schema::dropIfExists('recipe_ingredient');
    }
};
