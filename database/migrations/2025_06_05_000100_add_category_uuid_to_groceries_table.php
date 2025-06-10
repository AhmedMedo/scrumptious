<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('groceries', function (Blueprint $table) {
            $table->uuid('category_uuid')->nullable()->after('uuid');
            $table->foreign('category_uuid')->references('uuid')->on('grocery_category');
        });
    }

    public function down(): void
    {
        Schema::table('groceries', function (Blueprint $table) {
            $table->dropForeign(['category_uuid']);
            $table->dropColumn('category_uuid');
        });
    }
};
