<?php

namespace App\Libraries\DataTables\Column;

use App\Libraries\DataTables\Column\Contract\DataTableWithColumns;
use App\Libraries\DataTables\EloquentDataTable;

abstract class DataTableColumnDefinition
{
    protected DataTableWithColumns $dataTable;

    public function getDataTable(): DataTableWithColumns
    {
        return $this->dataTable;
    }

    public function setDataTable(DataTableWithColumns $dataTable): void
    {
        $this->dataTable = $dataTable;
    }

    public function bootEloquent(EloquentDataTable $dataTable): void
    {
        // boot eloquent data table
        // add some columns and filters to support by eloquent
        $this->addEloquentColumns($dataTable);
        $this->addEloquentFilters($dataTable);
    }

    /**
     * @return mixed[]
     */
    public function columns(): array
    {
        // columns definitions as array of strings
        return [];
    }

    /**
     * @return mixed[]
     */
    public function rawColumns(): array
    {
        // raw columns definitions as array of strings
        return [];
    }

    /**
     * @return mixed[]
     */
    public function searchableColumns(): array
    {
        // searchable columns definitions as array of strings
        return [];
    }

    /**
     * @return mixed[]
     */
    public function orderableColumns(): array
    {
        // columns definitions as array of strings
        return [];
    }

    protected function addEloquentColumns(EloquentDataTable $dataTable): void
    {
        // define eloquent columns
        // $dataTable->addColumn(...)
    }

    protected function addEloquentFilters(EloquentDataTable $dataTable): void
    {
        // define eloquent filters
        // $dataTable->addFilter(...)
    }
}
