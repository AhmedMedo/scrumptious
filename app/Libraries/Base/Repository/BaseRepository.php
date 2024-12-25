<?php

namespace App\Libraries\Base\Repository;

use App\Libraries\Base\DataTransferObject\DataTransferObjectable;

interface BaseRepository
{
    public function create(string|int $id, DataTransferObjectable $dto): void;

    public function update(string|int $id, DataTransferObjectable $dto): void;

    public function delete(string|int $id): void;

    public function deleteForce(string|int $id): void;
}
