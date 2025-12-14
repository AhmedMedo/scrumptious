<?php

use App\Components\MealPlanner\Data\Entity\PlanEntity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Populate names for existing plans
        PlanEntity::query()
            ->whereNull('name')
            ->with('user')
            ->chunk(100, function ($plans) {
                foreach ($plans as $plan) {
                    if (!$plan->user_uuid || !$plan->start_date || !$plan->end_date) {
                        continue;
                    }

                    $user = $plan->user;
                    if (!$user) {
                        continue;
                    }

                    $username = $user->email ?? $user->name ?? 'user';
                    $startDate = $plan->start_date->format('Y-m-d');
                    $endDate = $plan->end_date->format('Y-m-d');

                    $plan->update([
                        'name' => "{$username}-{$startDate}-{$endDate}"
                    ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set all names to null (optional - you may want to keep the names)
        DB::table('plans')->update(['name' => null]);
    }
};
