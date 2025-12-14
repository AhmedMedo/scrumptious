<?php

namespace App\Observers;

use App\Components\MealPlanner\Data\Entity\PlanEntity;

class PlanEntityObserver
{
    /**
     * Handle the PlanEntity "creating" event.
     */
    public function creating(PlanEntity $planEntity): void
    {
        $this->generateName($planEntity);
    }

    /**
     * Handle the PlanEntity "updating" event.
     */
    public function updating(PlanEntity $planEntity): void
    {
        // Only regenerate name if user_uuid, start_date, or end_date changed
        if ($planEntity->isDirty(['user_uuid', 'start_date', 'end_date'])) {
            $this->generateName($planEntity);
        }
    }

    /**
     * Handle the PlanEntity "created" event.
     */
    public function created(PlanEntity $planEntity): void
    {
        //
    }

    /**
     * Handle the PlanEntity "updated" event.
     */
    public function updated(PlanEntity $planEntity): void
    {
        //
    }

    /**
     * Generate name for plan: username-startDate-endDate
     */
    private function generateName(PlanEntity $planEntity): void
    {
        if (!$planEntity->user_uuid || !$planEntity->start_date || !$planEntity->end_date) {
            return;
        }

        // Get user directly from database to avoid relationship loading issues during create/update
        $user = \App\Components\Auth\Data\Entity\UserEntity::find($planEntity->user_uuid);
        if (!$user) {
            return;
        }

        $username = $user->email ?? $user->name ?? 'user';
        $startDate = $planEntity->start_date instanceof \Carbon\Carbon 
            ? $planEntity->start_date->format('Y-m-d')
            : \Carbon\Carbon::parse($planEntity->start_date)->format('Y-m-d');
        $endDate = $planEntity->end_date instanceof \Carbon\Carbon
            ? $planEntity->end_date->format('Y-m-d')
            : \Carbon\Carbon::parse($planEntity->end_date)->format('Y-m-d');

        $planEntity->name = "{$username}-{$startDate}-{$endDate}";
    }

    /**
     * Handle the PlanEntity "deleted" event.
     */
    public function deleted(PlanEntity $planEntity): void
    {
        //
    }

    /**
     * Handle the PlanEntity "restored" event.
     */
    public function restored(PlanEntity $planEntity): void
    {
        //
    }

    /**
     * Handle the PlanEntity "force deleted" event.
     */
    public function forceDeleted(PlanEntity $planEntity): void
    {
        //
    }
}
