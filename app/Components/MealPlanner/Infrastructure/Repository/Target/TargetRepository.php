<?php

namespace App\Components\MealPlanner\Infrastructure\Repository\Target;

use App\Components\MealPlanner\Application\Repository\Target\TargetRepositoryInterface;
use App\Components\MealPlanner\Data\Entity\TargetEntity;
use Carbon\Carbon;

class TargetRepository implements TargetRepositoryInterface
{

    public function create(array $data): TargetEntity
    {
        $data = array_merge($data , [
            'end_date' => Carbon::parse($data['start_date'])->addDays(7)->toDateString()
        ]);
        return TargetEntity::create($data);
    }

    public function update(string $uuid, array $data): void
    {
        TargetEntity::query()->where('uuid', $uuid)->update($data);
    }

    public function delete(string $uuid): void
    {
        TargetEntity::query()->where('uuid', $uuid)->delete();
    }
}
