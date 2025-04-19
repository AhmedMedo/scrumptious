<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class RecipeEntityFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function title($title): RecipeEntityFilter
    {
        return $this->where('title', 'like', '%'.$title.'%');
    }

    public function isFavorite(bool $isFavorite = true): RecipeEntityFilter
    {
//        dd($isFavorite);
        $user = auth()->user();
        if (!$user) {
            return $isFavorite
                ? $this->whereRaw('1 = 0') // No results for guests when requesting favorites
                : $this; // All recipes for guests when requesting non-favorites
        }

        return $this->whereHas('favoriters', function($query) use ($user) {
            $query->where(config('favorite.user_foreign_key'), $user->getKey());
        }, $isFavorite ? '>=' : '=', $isFavorite ? 1 : 0);
    }
}
