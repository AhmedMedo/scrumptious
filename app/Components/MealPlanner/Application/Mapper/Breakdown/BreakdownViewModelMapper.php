<?php

namespace App\Components\MealPlanner\Application\Mapper\Breakdown;

use App\Components\MealPlanner\Data\Entity\MealPlanBreakdownEntity;
use App\Components\MealPlanner\Presentation\ViewModel\Breakdown\BreakdownViewModel;

class BreakdownViewModelMapper
{
    public function fromEntity(MealPlanBreakdownEntity $breakdownEntity): BreakdownViewModel
    {
        // Ensure plan is loaded
        if (!$breakdownEntity->relationLoaded('plan')) {
            $breakdownEntity->load('plan');
        }

        $plan = $breakdownEntity->plan;

        return new BreakdownViewModel(
            uuid: $breakdownEntity->getKey(),
            planUuid: $plan->uuid,
            planName: $plan->name,
            planStartDate: $plan->start_date,
            planEndDate: $plan->end_date,
            date: $breakdownEntity->date,
            meals: $breakdownEntity->meals
        );
    }
}

