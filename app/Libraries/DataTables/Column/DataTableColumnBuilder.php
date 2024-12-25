<?php

namespace App\Libraries\DataTables\Column;

use App\Libraries\DataTables\Column\Contract\DataTableWithColumns;
use App\Libraries\DataTables\EloquentDataTable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;

final class DataTableColumnBuilder
{
    private Application $application;

    private ?DataTableColumnCollection $columnCollection = null;

    private ?DataTableWithColumns $dataTable = null;

    private bool $mergeColumns = false;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function initialize(DataTableWithColumns $dataTable): self
    {
        // set datatable as property
        // initialize columns collection from datatable
        // set columns in data table from columns collection data
        $this->setDataTable($dataTable);
        $this->initializeDataTableColumnCollection();
        $this->initializeDataTableColumns();

        return $this;
    }

    public function eloquentColumns(EloquentDataTable $dataTable): EloquentDataTable
    {
        // for all data table column definition
        // boot eloquent columns and filters
        foreach ($this->getColumnCollection()->all() as $columnDefinition) {
            $columnDefinition->bootEloquent($dataTable);
        }

        return $dataTable;
    }

    public function setDataTable(DataTableWithColumns $dataTable): void
    {
        $this->dataTable = $dataTable;
    }

    public function getDataTable(): ?DataTableWithColumns
    {
        return $this->dataTable;
    }

    public function setColumnCollection(DataTableColumnCollection $columnCollection): void
    {
        $this->columnCollection = $columnCollection;
    }

    public function getColumnCollection(): ?DataTableColumnCollection
    {
        return $this->columnCollection;
    }

    private function initializeDataTableColumnCollection(): void
    {
        $columnCollection = Collection::make($this->getDataTable()->getDataTableColumns())
            ->map(fn (string $column) => $this->application->make($column))
            ->each(function (DataTableColumnDefinition $columnDefinition): void {
                $columnDefinition->setDataTable($this->getDataTable());
            });

        $this->setColumnCollection(new DataTableColumnCollection($columnCollection));
    }

    public function mergeColumns(): self
    {
        $this->mergeColumns = true;

        return $this;
    }

    public function replaceColumns(): self
    {
        $this->mergeColumns = false;

        return $this;
    }

    private function initializeDataTableColumns(): void
    {
        $columns = $this->getColumnCollection()->getColumns();
        $rawColumns = $this->getColumnCollection()->getRawColumns();
        $searchableColumns = $this->getColumnCollection()->getSearchableColumns();
        $orderableColumns = $this->getColumnCollection()->getOrderableColumns();

        if ($this->mergeColumns) {
            $columns = $this->handleColumnsMerge($columns, $this->dataTable->getColumns());
            $rawColumns = $this->handleColumnsMerge($rawColumns, $this->dataTable->getRawColumns());
            $searchableColumns = $this->handleColumnsMerge($searchableColumns, $this->dataTable->getSearchableColumns());
            $orderableColumns = $this->handleColumnsMerge($orderableColumns, $this->dataTable->getOrderableColumns());
        }

        $this->dataTable->setColumns($columns);
        $this->dataTable->setRawColumns($rawColumns);
        $this->dataTable->setSearchableColumns($searchableColumns);
        $this->dataTable->setOrderableColumns($orderableColumns);
    }

    /**
     * @return mixed[]
     */
    private function handleColumnsMerge(array $columns, array $dataTableColumns): array
    {
        if (! $dataTableColumns) {
            return [];
        }

        return Collection::make($columns)
            ->merge($dataTableColumns)
            ->values()
            ->unique()
            ->values()
            ->when(1, function (Collection $columns, int $minCount): Collection {
                if ($columns->count() > $minCount) {
                    return $columns->diff(['*']);
                }

                return $columns;
            })
            ->toArray();
    }
}
