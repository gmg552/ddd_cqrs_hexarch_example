<?php

namespace Qalis\Shared\Domain\SpreadsheetBatchCommands;


use Qalis\Shared\Application\SpreadsheetBatchCommands\FindToRun\SpreadsheetBatchCommandToRunResponse;
use Qalis\Shared\Application\SpreadsheetBatchCommands\Search\SearchSpreadsheetBatchCommandsResponse;

interface SpreadsheetBatchCommandReadRepository {
    public function search(string $batchCommandCode) : SearchSpreadsheetBatchCommandsResponse;
    public function findToRun(string $spreadsheetFormatId): SpreadsheetBatchCommandToRunResponse;
}
