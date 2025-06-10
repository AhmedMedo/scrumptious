<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class GroceryCategoryEntityFilter extends ModelFilter
{
    public $relations = [];

    public function name($name): GroceryCategoryEntityFilter
    {
        return $this->where('name', 'like', '%'.$name.'%');
    }
}
