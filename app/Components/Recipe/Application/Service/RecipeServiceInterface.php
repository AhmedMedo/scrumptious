<?php

namespace App\Components\Recipe\Application\Service;

interface RecipeServiceInterface
{

    public function all();

    public function store(array $data);

    public function update(string $uuid, array $data);

    public function delete(string $uuid);

    public function findByUuid(string $uuid);

}
