<?php

namespace Qalis\Shared\Application\SpreadsheetBatchCommands\Search;

final class SearchSpreadsheetBatchesQueryHandler
{
    private SpreadsheetBatchCommandsSearcher $spreadsheetBatchCommandsSearcher;

    public function __construct(SpreadsheetBatchCommandsSearcher $spreadsheetBatchCommandsSearcher)
    {
        $this->spreadsheetBatchCommandsSearcher = $spreadsheetBatchCommandsSearcher;
    }

    public function __invoke(SearchSpreadsheetBatchesQuery $query): SearchSpreadsheetBatchCommandsResponse
    {
        return $this->spreadsheetBatchCommandsSearcher->__invoke($query->batchCommandCode());
    }
}
