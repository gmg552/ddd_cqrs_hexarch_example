<?php

namespace Qalis\Shared\Application\SpreadsheetBatchCommands\Search;

use Qalis\Shared\Domain\SpreadsheetBatchCommands\SpreadsheetBatchCommandReadRepository;

final class SpreadsheetBatchCommandsSearcher
{
    private SpreadsheetBatchCommandReadRepository $spreadsheetBatchCommandReadRepository;

    public function __construct(SpreadsheetBatchCommandReadRepository $spreadsheetBatchCommandReadRepository)
    {
        $this->spreadsheetBatchCommandReadRepository = $spreadsheetBatchCommandReadRepository;
    }

    public function __invoke(string $batchCommandCode): SearchSpreadsheetBatchCommandsResponse
    {
        return $this->spreadsheetBatchCommandReadRepository->search($batchCommandCode);
    }
}
