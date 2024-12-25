<?php

namespace App\Libraries\DataTables\Traits;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Throwable;

trait HasDateFilterDataTableTrait
{
    /**
     * @param string|null|Carbon $candidate
     */
    private function createDateFromCandidate($candidate): ?CarbonInterface
    {
        try {
            return Carbon::parse($candidate);
        } catch (Throwable $exception) {
            return null;
        }
    }

    /**
     * @param string $table
     * @param string $column
     * @param mixed|Builder $query
     * @param mixed|string $keywords
     * @param string $keywordColumn
     */
    private function filterByDate(string $table, string $column, $query, $keywords, string $keywordColumn = ''): void
    {
        $keywordColumn = $keywordColumn ?: $column;
        $canCheckColumnKeyword = method_exists($this, 'columnHasKeyword');
        $missingKeyword = $canCheckColumnKeyword && ! $this->columnHasKeyword($keywordColumn);
        $dates = $this->parseDatesCandidates($keywords);

        if ($missingKeyword || ! $this->hasDatesToFilter($dates)) {
            return;
        }
        $queryColumn = sprintf('%s.%s', $table, $column);
        $fromDate = $this->parseDateFromCandidates($dates, 0);
        $untilDate = $this->parseDateFromCandidates($dates, 1);

        if ($fromDate instanceof Carbon) {
            $query->whereDate($queryColumn, '>=', $fromDate);
        }

        if ($untilDate instanceof Carbon) {
            $query->whereDate($queryColumn, '<=', $untilDate);
        }
    }

    private function hasDatesToFilter(array $dates): bool
    {
        return collect($dates)->filter()->isNotEmpty();
    }

    private function isValidDateCandidate(?string $date = null): bool
    {
        return (bool) strtotime((string) $date);
    }

    /**
     * @return mixed[]
     */
    private function parseDatesCandidates(string $candidates): array
    {
        $dates = json_decode($candidates);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        if (! is_array($dates)) {
            return [];
        }

        return array_values($dates);
    }

    private function parseDateFromCandidates(array $candidates, int $candidateIndex): ?CarbonInterface
    {
        $dateCandidate = Arr::get($candidates, $candidateIndex);
        $date = null;

        if ($this->isValidDateCandidate($dateCandidate)) {
            $date = $this->createDateFromCandidate($dateCandidate);
        }

        return $date;
    }
}
