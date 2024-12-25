<?php

declare(strict_types=1);

namespace App\Libraries\Support;

use App\Component\Common\Application\Mapper\EntityCollectionToViewModelCollectionMapper;
use Illuminate\Pagination\LengthAwarePaginator;

class ViewModelPaginated
{
    public static function map(
        LengthAwarePaginator $resource,
        EntityCollectionToViewModelCollectionMapper $mapper
    ): LengthAwarePaginator {
        return $resource->setCollection(
            $mapper->map(
                collect($resource->items())
            )
        );
    }
}
