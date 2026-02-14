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
        Schema::create('admin_broadcasts', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('admin_uuid');
            $table->string('title');
            $table->text('body');
            $table->json('data')->nullable();
            $table->enum('target_type', ['all', 'specific'])->default('all');
            $table->json('target_user_uuids')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'sent', 'failed'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('total_recipients')->default(0);
            $table->integer('successful_sends')->default(0);
            $table->integer('failed_sends')->default(0);
            $table->timestamps();

            $table->foreign('admin_uuid')->references('uuid')->on('admins')->onDelete('cascade');
            $table->index('admin_uuid');
            $table->index('status');
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_broadcasts');
    }
};
