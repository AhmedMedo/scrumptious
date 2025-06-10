<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class GroceryEntityFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];


    public function content($content): GroceryEntityFilter
    {
        return $this->where('content', 'like', '%'.$content.'%');
    }

    public function categoryUuid($categoryUuid): GroceryEntityFilter
    {
        return $this->where('category_uuid', $categoryUuid);
    }
}
