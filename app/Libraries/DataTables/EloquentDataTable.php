<?php

namespace App\Libraries\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\EloquentDataTable as BaseEloquentDataTable;

class EloquentDataTable extends BaseEloquentDataTable
{
    /** @var string[] */
    private array $counter = ['*'];

    /**
     * {@inheritdoc}
     */
    protected function render(array $data): JsonResponse
    {
        $output = $this->attachAppends([
            'draw'            => (int) $this->request->input('draw'),
            'recordsTotal'    => $this->totalRecords,
            'recordsFiltered' => $this->filteredRecords,
            'data'            => $data,
        ]);

        $output = $this->showDebugger($output);

        if (! $this->config->isDebugging()) {
            unset($output['queries']);
        }

        return new JsonResponse(data: $output);
    }

    /**
     * @param string[] $counter
     */
    public function setCounter(array $counter): self
    {
        $this->counter = $counter;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        $builder = $this->getCleanBuilderInstance();
        $isDashboard = $this->request->header('platform') === 'dashboard';
        $ttl = $isDashboard ? 30 : 5;

        return (int) Cache::remember(
            $this->getBuilderCacheKey($builder),
            Carbon::now()->addMinutes($ttl),
            fn () => $builder->toBase()->getCountForPagination($this->counter)
        );
    }

    private function getBuilderCacheKey(Builder $builder): string
    {
        return sprintf('datatable_count_sql_%s', md5(implode('|', [
            $builder->toSql(),
            implode(',', $builder->getBindings()),
        ])));
    }

    private function getCleanBuilderInstance(): Builder
    {
        $builder = clone $this->query;

        $builder->toBase()->unions = null;
        $builder->toBase()->groups = null;
        $builder->toBase()->orders = null;

        return $builder;
    }
}
