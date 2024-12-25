<?php

namespace App\Libraries\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\DataTables as BaseDataTables;
use Yajra\DataTables\EloquentDataTable as BaseEloquentDataTable;

class DataTables extends BaseDataTables
{
    /**
     * @param Builder|mixed $builder
     *
     * @return BaseEloquentDataTable
     */
    public function eloquent($builder): BaseEloquentDataTable
    {
        return EloquentDataTable::create($builder);
    }
}
