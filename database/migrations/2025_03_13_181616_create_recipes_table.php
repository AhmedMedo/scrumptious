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
        Schema::create('recipes', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('title');
            $table->integer('cooking_minutes')->nullable();
            $table->float('total_carbs')->nullable();
            $table->float('total_proteins')->nullable();
            $table->float('total_fats')->nullable();
            $table->float('total_calories')->nullable();
            $table->string('youtube_video')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
