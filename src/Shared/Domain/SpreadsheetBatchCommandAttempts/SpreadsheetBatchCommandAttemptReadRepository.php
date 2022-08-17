<?php

namespace Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts;

use Qalis\Shared\Application\SpreadhseetBatchCommandAttempts\DownloadFile\SpreadsheetBatchCommandAttemptResponse;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SearchToList\SearchBatchCommandAttemptsToListResponse;

interface SpreadsheetBatchCommandAttemptReadRepository {
    public function searchToList(string $batchCommandCode, array $contextParams, ?int $limit = null, ?int $offset = null) : SearchBatchCommandAttemptsToListResponse;
    public function find(string $batchCommandAttemptId): SpreadsheetBatchCommandAttemptResponse;
}
