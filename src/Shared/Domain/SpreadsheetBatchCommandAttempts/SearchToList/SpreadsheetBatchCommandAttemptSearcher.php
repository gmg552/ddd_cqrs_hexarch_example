<?php

namespace Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SearchToList;

use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\GetFileLink\SpreadsheetBatchCommandAttemptFileLinkGetter;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SearchToList\SearchBatchCommandAttemptsToListResponse;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SpreadsheetBatchCommandAttemptReadRepository;

final class SpreadsheetBatchCommandAttemptSearcher {

    private SpreadsheetBatchCommandAttemptReadRepository $spreadsheetBatchCommandAttemptReadRepository;

    public function __construct(
        SpreadsheetBatchCommandAttemptReadRepository $spreadsheetBatchCommandAttemptReadRepository
    )
    {
        $this->spreadsheetBatchCommandAttemptReadRepository = $spreadsheetBatchCommandAttemptReadRepository;
    }

    public function __invoke(string $batchCommandCode, array $contextParams, ?int $limit = null, ?int $offset = null) : SearchBatchCommandAttemptsToListResponse
    {
        return $this->spreadsheetBatchCommandAttemptReadRepository->searchToList($batchCommandCode, $contextParams, $limit, $offset);
    }

}


