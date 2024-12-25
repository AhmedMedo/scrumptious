<?php

namespace App\Libraries\DataTables\Column\Contract;

interface DataTableWithColumns
{
    /**
     * @return mixed[]
     */
    public function getDataTableColumns(): array;

    /**
     * @return mixed[]
     */
    public function getColumns(): array;

    public function setColumns(array $columns): void;

    /**
     * @return mixed[]
     */
    public function getRawColumns(): array;

    public function setRawColumns(array $columns): void;

    /**
     * @return mixed[]
     */
    public function getSearchableColumns(): array;

    public function setSearchableColumns(array $columns): void;

    /**
     * @return mixed[]
     */
    public function getOrderableColumns(): array;

    public function setOrderableColumns(array $columns): void;
}
