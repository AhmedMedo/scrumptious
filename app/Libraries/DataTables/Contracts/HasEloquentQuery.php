<?php

namespace App\Libraries\DataTables\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface HasEloquentQuery
{
    public function getEloquentQuery(): Builder;
}
