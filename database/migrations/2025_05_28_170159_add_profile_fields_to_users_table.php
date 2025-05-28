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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nationality')->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('email');

            $table->decimal('weight', 8, 2)->nullable()->after('birth_date');
            $table->string('weight_unit', 10)->nullable()->after('weight');

            $table->decimal('height', 8, 2)->nullable()->after('weight_unit');
            $table->string('height_unit', 10)->nullable()->after('height');

            $table->string('user_diet')->nullable()->after('height_unit');
            $table->string('goal')->nullable()->after('user_diet');

            $table->boolean('have_allergies')->nullable()->after('goal');
            $table->json('allergies')->nullable()->after('have_allergies');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'weight',
                'weight_unit',
                'height',
                'height_unit',
                'user_diet',
                'goal',
                'have_allergies',
                'allergies',
                'nationality',
            ]);
        });
    }
};
