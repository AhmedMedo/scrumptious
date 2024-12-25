<?php

namespace App\Components\Content\Application\Mapper;

use Illuminate\Support\Collection;

class CountryViewMapper
{
    public function toArray(Collection $collection): array
    {
        $data = [];
        foreach ($collection as $country) {
            $data[] = [
                'uuid' => $country->uuid,
                'name' => $country->name,
                'flag' => $country->flag,
                'iso_code' => $country->iso_code,
                'iso3_code' => $country->iso3_code,
                'created_at' => $country->created_at,
            ];
        }
        return $data;
    }
}
