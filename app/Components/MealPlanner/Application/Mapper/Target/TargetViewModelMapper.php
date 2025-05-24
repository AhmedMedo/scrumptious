<?php

namespace App\Components\MealPlanner\Application\Mapper\Target;

use App\Components\MealPlanner\Data\Entity\TargetEntity;
use App\Components\MealPlanner\Presentation\ViewModel\Target\TargetViewModel;

class TargetViewModelMapper
{
    public function fromEntity(TargetEntity $entity): TargetViewModel
    {
        return new TargetViewModel(
            uuid: $entity->getKey(),
            title: $entity->title,
            startDate: $entity->start_date,
            endDate: $entity->end_date,
            timeframe: $entity->timeframe,
            description: $entity->description,
        );
    }
}
