<?php

namespace App\Components\Recipe\Infrastructure\Repository;

use App\Components\Recipe\Application\Repository\IngredientRepositoryInterface;
use App\Components\Recipe\Data\Entity\IngredientEntity;
use Illuminate\Support\Arr;

class IngredientRepository implements IngredientRepositoryInterface
{

    public function create(array $data): IngredientEntity
    {
        return IngredientEntity::query()->firstOrCreate([
            'content' => Arr::get($data, 'content'),
        ]);
    }

    public function update(string $uuid, array $data): void
    {
        IngredientEntity::query()->where('uuid', $uuid)->update([
            'content' => Arr::get($data, 'content'),
        ]);
    }

    public function delete(string $uuid): void
    {
        IngredientEntity::query()->where('uuid', $uuid)->delete();
    }
}
