<?php

namespace Qalis\Shared\Application\BatchCommandAttempts\Delete;

use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\Create\SpreadsheetBatchCommandAttemptCreator;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SpreadsheetBatchCommandAttemptReadRepository;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SpreadsheetBatchCommandAttemptRepository;
use Qalis\Shared\Domain\Storage\StorageProvider;
use Qalis\Shared\Domain\Utils\StringUtils;

final class BatchCommandAttemptsRemover {

    private SpreadsheetBatchCommandAttemptReadRepository $spreadsheetBatchCommandAttemptReadRepository;
    private SpreadsheetBatchCommandAttemptRepository $spreadsheetBatchCommandAttemptRepository;
    private StorageProvider $storageProvider;

    public function __construct(
        SpreadsheetBatchCommandAttemptReadRepository $spreadsheetBatchCommandAttemptReadRepository,
        SpreadsheetBatchCommandAttemptRepository $spreadsheetBatchCommandAttemptRepository,
        StorageProvider $storageProvider
    )
    {
        $this->spreadsheetBatchCommandAttemptReadRepository = $spreadsheetBatchCommandAttemptReadRepository;
        $this->storageProvider = $storageProvider;
        $this->spreadsheetBatchCommandAttemptRepository = $spreadsheetBatchCommandAttemptRepository;
    }

    public function __invoke(string $batchCommandAttemptId): void
    {
        $this->removeSpreadsheetBatchCommandAttempt($batchCommandAttemptId);
    }

    private function removeSpreadsheetBatchCommandAttempt(string $batchCommandAttemptId): void
    {
        $spreadsheetBatchCommandAttempt = $this->spreadsheetBatchCommandAttemptReadRepository->find($batchCommandAttemptId);
        $this->storageProvider->remove(SpreadsheetBatchCommandAttemptCreator::SPREADSHEET_BATCH_COMMAND_STORAGE_BASE_PATH. "/". StringUtils::toSnakeCase($spreadsheetBatchCommandAttempt->commandCode()), $spreadsheetBatchCommandAttempt->fileName());
        $this->spreadsheetBatchCommandAttemptRepository->delete($batchCommandAttemptId);
    }

}
