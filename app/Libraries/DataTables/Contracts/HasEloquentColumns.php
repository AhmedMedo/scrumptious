<?php

/**
 * Date: 01/09/2019
 * Time: 22:55
 *
 * @author Artur Bartczak <artur.bartczak@proexe.pl>
 */

namespace App\Libraries\DataTables\Contracts;

use App\Libraries\DataTables\EloquentDataTable;

interface HasEloquentColumns
{
    public function getEloquentColumns(EloquentDataTable $dataTable): EloquentDataTable;
}
