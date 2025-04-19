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
        Schema::create(config('favorite.favorites_table'), function (Blueprint $table) {
            $table->uuid();
            $table->uuid(config('favorite.user_foreign_key'))->index()->comment('user_uuid');
            $table->uuid('favoriteable_uuid');  // Stores the UUID of the related model
            $table->string('favoriteable_type'); // Stores the class name of the related model
            $table->timestamps();
            $table->index(['favoriteable_uuid', 'favoriteable_type']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(config('favorite.favorites_table'));
    }
};
