<?php

namespace App\Libraries\DataTables\Column;

use Illuminate\Support\Collection;
use Webmozart\Assert\Assert;

final class DataTableColumnCollection
{
    private const WILDCARD = '*';
    private const DEFAULT_SEARCHABLE_COLUMNS = [self::WILDCARD];
    private const DEFAULT_ORDERABLE_COLUMNS = [self::WILDCARD];

    private array $columns = [];

    private array $rawColumns = [];

    private array $searchableColumns = self::DEFAULT_SEARCHABLE_COLUMNS;

    private array $orderableColumns = self::DEFAULT_ORDERABLE_COLUMNS;

    /** @var Collection|DataTableColumnDefinition[] */
    private Collection $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection->whereInstanceOf(DataTableColumnDefinition::class);
        $this->initialize();
    }

    /**
     * @return string[]|int[]
     */
    public function getColumns(): array
    {
        // get array keys because
        // columns names are array keys, column definition is array value
        return array_keys($this->columns);
    }

    /**
     * @return string[]|int[]
     */
    public function getRawColumns(): array
    {
        // get array keys because
        // columns names are array keys, column definition is array value
        return array_keys($this->rawColumns);
    }

    /**
     * @return mixed[]|int[]|string[]
     */
    public function getSearchableColumns(): array
    {
        // if columns includes only wildcard,
        // return wildcard
        if ($this->includesOnlyWildcard($this->searchableColumns)) {
            return array_values($this->searchableColumns);
        }

        // get array keys because
        // columns names are array keys, column definition is array value
        return array_keys($this->searchableColumns);
    }

    /**
     * @return mixed[]|int[]|string[]
     */
    public function getOrderableColumns(): array
    {
        // if columns includes only wildcard,
        // return wildcard
        if ($this->includesOnlyWildcard($this->orderableColumns)) {
            return array_values($this->orderableColumns);
        }

        // get array keys because
        // columns names are array keys, column definition is array value
        return array_keys($this->orderableColumns);
    }

    /** @return Collection|DataTableColumnDefinition[] */
    public function all(): Collection
    {
        return new Collection($this->collection->all());
    }

    private function initialize(): void
    {
        // merge columns from all columns definitions
        // check for column names uniqueness and format
        foreach ($this->collection as $columnDefinition) {
            $this->mergeColumns($columnDefinition);
            $this->mergeRawColumns($columnDefinition);
            $this->mergeSearchableColumns($columnDefinition);
            $this->mergeOrderableColumns($columnDefinition);
        }
    }

    private function mergeColumns(DataTableColumnDefinition $columnDefinition): void
    {
        foreach ($columnDefinition->columns() as $column) {
            $this->addColumn($column, $this->columns, $columnDefinition);
        }
    }

    private function mergeRawColumns(DataTableColumnDefinition $columnDefinition): void
    {
        foreach ($columnDefinition->rawColumns() as $column) {
            $this->addColumn($column, $this->rawColumns, $columnDefinition);
        }
    }

    private function mergeSearchableColumns(DataTableColumnDefinition $columnDefinition): void
    {
        foreach ($columnDefinition->searchableColumns() as $column) {
            $this->addColumn($column, $this->searchableColumns, $columnDefinition);
        }

        $this->eliminateWildcardIfNeeded($this->searchableColumns);
    }

    private function mergeOrderableColumns(DataTableColumnDefinition $columnDefinition): void
    {
        foreach ($columnDefinition->orderableColumns() as $column) {
            $this->addColumn($column, $this->orderableColumns, $columnDefinition);
        }

        $this->eliminateWildcardIfNeeded($this->orderableColumns);
    }

    private function addColumn(string $column, array &$columns, DataTableColumnDefinition $columnDefinition): void
    {
        Assert::stringNotEmpty($column, 'Column name cannot be empty.');
        // check if column name is not already defined,
        // if it is, tell me where
        Assert::keyNotExists($columns, $column, sprintf(
            "Column [%s] is has duplicated definition in [%s] class. Previously defined in [%s]",
            $column,
            get_class($columnDefinition),
            $columns[$column] ?? null
        ));

        // save column name as key, column definition as value
        // that is the best way to save location column definition
        $columns[$column] = get_class($columnDefinition);
    }

    private function eliminateWildcardIfNeeded(array &$columns): void
    {
        // some columns can include wildcards
        // if column does not include only wildcard
        // that means we should eliminate wildcard from columns
        if (! $this->includesOnlyWildcard($columns)) {
            $columns = array_diff($columns, [self::WILDCARD]);
        }
    }

    private function includesOnlyWildcard(array $columns): bool
    {
        return in_array(self::WILDCARD, $columns, true) && count($columns) === 1;
    }
}
