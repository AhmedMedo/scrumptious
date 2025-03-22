<?php

namespace App\Components\MealPlanner\Infrastructure\Service\Target;

use App\Components\MealPlanner\Application\Query\Target\TargetQueryInterface;
use App\Components\MealPlanner\Application\Repository\Target\TargetRepositoryInterface;
use App\Components\MealPlanner\Application\Service\Target\TargetServiceInterface;
use App\Components\MealPlanner\Data\Entity\TargetEntity;
use Illuminate\Pagination\LengthAwarePaginator;

class TargetService implements TargetServiceInterface
{


    public function __construct(
        private readonly TargetRepositoryInterface $repository,
        private readonly TargetQueryInterface $query
    )
    {
    }

    public function paginated(string $userUuid): LengthAwarePaginator
    {
       return  $this->query->paginated($userUuid);
    }

    public function all(): \Illuminate\Support\Collection
    {
        return $this->query->all();
    }

    public function store(array $data): TargetEntity
    {
       return $this->repository->create($data);
    }

    public function update(string $uuid, array $data): void
    {
        $this->repository->update($uuid, $data);
    }

    public function delete(string $uuid): void
    {
        $this->repository->delete($uuid);
    }

    public function findByUuid(string $uuid)
    {
        return $this->query->findByUuid($uuid);
    }
}
