<?php

namespace App\Libraries\DataTables;

use App\Libraries\DataTables\Contracts\HasEloquentColumns;
use App\Libraries\DataTables\Contracts\HasEloquentQuery;
use App\Libraries\DataTables\Exceptions\DataTablesException;
use Exception;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\EloquentDataTable as BaseEloquentDataTable;

abstract class DataTablesAbstract extends DataTables implements HasEloquentQuery, HasEloquentColumns
{
    protected array $columns = [];

    protected array $rawColumns = [];

    protected array $searchable = ['*'];

    protected array $orderable = ['*'];

    /**
     * @return mixed
     * @throws Exception
     */
    public function response(): array
    {
        $query = $this->getEloquentQuery()->applyScopes();
        $eloquentDataTable = $this->fixRequest($this->eloquent($query));

        if ($this->columns) {
            $eloquentDataTable->only($this->columns);
        }

        if ($this->rawColumns) {
            $eloquentDataTable->rawColumns($this->rawColumns);
        }

        if ($metadata = $this->getMetadata($eloquentDataTable)) {
            $eloquentDataTable->with('metadata', $metadata);
        }

        $eloquentDataTable = $this->getEloquentColumns($eloquentDataTable);

        $data = $eloquentDataTable->make(true);

        if (is_array($data)) {
            return $data;
        }

        if ($data instanceof JsonResponse) {
            $content = json_decode($data->getContent(), true);

            if (isset($content['error'])) {
                throw new DataTablesException($content['error']);
            }

            return $content;
        } else {
            throw new DataTablesException("Unexpected data type: " . get_class($data));
        }
    }

    public function getMetadata(EloquentDataTable $eloquentDataTable): array|null
    {
        return null;
    }

    /**
     * @param EloquentDataTable $eloquentDataTable
     * @return array
     */
    public function getRequestColumns(BaseEloquentDataTable $eloquentDataTable): array
    {
        $columnsFields = [];
        foreach ($this->columns as $index => $column) {
            $columnsFields['columns'][$index]['name'] = $column;
            $columnsFields['columns'][$index]['searchable'] = $this->isSearchableColumn($column);
            $columnsFields['columns'][$index]['orderable'] = $this->isOrderableColumn($column);
            $columnsFields['columns'][$index]['search']['value'] = $this->getColumnSearchValue(
                $eloquentDataTable->request->get('columns'),
                $index
            );
        }

        return $columnsFields;
    }

    final public function columnIndex(string $column): int
    {
        return array_search($column, $this->columns, true);
    }

    /**
     * @param string $column
     * @return array|string
     */
    public function columnKeyword(string $column)
    {
        return $this->getRequest()->columnKeyword($this->columnIndex($column));
    }

    public function columnHasKeyword(string $column): bool
    {
        return ! in_array((string) $this->columnKeyword($column), ['', 'all', 'null']);
    }

    public function columnMissingKeyword(string $column): bool
    {
        return $this->columnHasKeyword($column) === false;
    }

    public function parseKeywordIntoInt(string $keyword): ?int
    {
        if (strpos($keyword, '.') !== false || strpos($keyword, ',') !== false) {
            $search = ['.', ','];
            $replace = [''];

            return intval(str_replace($search, $replace, $keyword));
        }

        return null;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param mixed[] $columns
     */
    public function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }

    public function getRawColumns(): array
    {
        return $this->rawColumns;
    }

    /**
     * @param mixed[] $columns
     */
    public function setRawColumns(array $columns): void
    {
        $this->rawColumns = $columns;
    }

    public function getSearchableColumns(): array
    {
        return $this->searchable;
    }

    /**
     * @param mixed[] $columns
     */
    public function setSearchableColumns(array $columns): void
    {
        $this->searchable = $columns;
    }

    public function getOrderableColumns(): array
    {
        return $this->orderable;
    }

    /**
     * @param mixed[] $columns
     */
    public function setOrderableColumns(array $columns): void
    {
        $this->orderable = $columns;
    }

    private function fixRequest(BaseEloquentDataTable $eloquentDataTable): BaseEloquentDataTable
    {
        $columnsFields = $this->getRequestColumns($eloquentDataTable);
        $eloquentDataTable->request->merge($columnsFields);

        return $eloquentDataTable;
    }

    /**
     * @return mixed|null
     */
    private function getColumnSearchValue(?array $columns, int $index)
    {
        return $columns[$index]['search']['value'] ?? null;
    }

    private function isSearchableColumn(string $column): bool
    {
        if (
            in_array('*', $this->searchable) ||
            in_array($column, $this->searchable)
        ) {
            return true;
        }

        return false;
    }

    private function isOrderableColumn(string $column): bool
    {
        if (
            in_array('*', $this->orderable) ||
            in_array($column, $this->orderable)
        ) {
            return true;
        }

        return false;
    }
}
