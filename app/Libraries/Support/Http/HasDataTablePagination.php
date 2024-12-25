<?php

namespace App\Libraries\Support\Http;

use Illuminate\Http\Request;

trait HasDataTablePagination
{
    /**
     * @return array{start: string[], length: string[]}
     */
    public function rules(): array
    {
        return [
            'start'  => [
                'nullable',
                'integer',
                sprintf('min:%s', $this->getDefaultPaginationStart()),
            ],
            'length' => [
                'nullable',
                'integer',
                sprintf('min:%s', $this->getDefaultPaginationLength()),
            ],
        ];
    }

    protected function getDefaultPaginationStart(): int
    {
        return 0;
    }

    protected function getDefaultPaginationLength(): int
    {
        return 1;
    }

    /**
     * @return array{start: mixed, length: mixed}
     */
    public function getPagination(Request $request): array
    {
        return [
            'start'  => $this->getStart($request),
            'length' => $this->getLength($request),
        ];
    }

    public function getStart(Request $request): int
    {
        return max((int) $request->input('start'), $this->getDefaultPaginationStart());
    }

    public function getLength(Request $request): int
    {
        return max((int) $request->input('length'), $this->getDefaultPaginationLength());
    }
}
