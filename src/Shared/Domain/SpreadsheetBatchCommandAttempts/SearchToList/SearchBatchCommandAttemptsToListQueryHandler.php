<?php

declare(strict_types=1);

namespace Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SearchToList;

final class SearchBatchCommandAttemptsToListQueryHandler
{
    private SpreadsheetBatchCommandAttemptSearcher $spreadsheetBatchCommandAttemptSearcher;

    public function __construct(SpreadsheetBatchCommandAttemptSearcher $spreadsheetBatchCommandAttemptSearcher)
    {
        $this->spreadsheetBatchCommandAttemptSearcher = $spreadsheetBatchCommandAttemptSearcher;
    }

    public function __invoke(SearchBatchCommandAttemptsToListQuery $query): SearchBatchCommandAttemptsToListResponse
    {
        return $this->spreadsheetBatchCommandAttemptSearcher->__invoke($query->batchCommandCode(), $query->contextParams(), $query->limit(), $query->offset());
    }
}
