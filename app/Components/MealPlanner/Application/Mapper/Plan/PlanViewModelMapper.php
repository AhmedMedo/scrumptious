<?php

namespace App\Components\MealPlanner\Application\Mapper\Plan;

use App\Components\MealPlanner\Data\Entity\PlanEntity;
use App\Components\MealPlanner\Presentation\ViewModel\Plan\PlanViewModel;

class PlanViewModelMapper
{
    public function fromEntity(PlanEntity $planEntity): PlanViewModel
    {
        return new PlanViewModel(
            uuid: $planEntity->getKey(),
            startDate: $planEntity->start_date,
            endDate: $planEntity->end_date,
            type: $planEntity->type,
            meals: $planEntity->meals
        );
    }

}
