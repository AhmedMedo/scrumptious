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
        //drop foreign key for recipe_uuid

        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->dropForeign(['recipe_uuid']);
        });
        //rename to ingredients

        Schema::rename('recipe_ingredients', 'ingredients');
        //remove recipe_uuid

        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn('recipe_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //reverse

        Schema::rename('ingredients', 'recipe_ingredients');
        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->foreign('recipe_uuid')->references('uuid')->on('recipes')->onDelete('cascade');
        });
    }
};
