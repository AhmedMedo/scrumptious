<?php

namespace App\Libraries\Curl\Response;

use Illuminate\Support\Collection;

final class Result
{
    private string $data;

    private Collection $info;

    /**
     *
     * @param string $data
     * @param array $info
     */
    public function __construct(string $data, array $info)
    {
        $this->data = $data;
        $this->info = new Collection($info);
    }

    public function data(): string
    {
        return $this->data;
    }

    public function info(): Collection
    {
        return $this->info;
    }
}
