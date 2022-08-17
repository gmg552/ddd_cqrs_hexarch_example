<?php

namespace Qalis\Shared\Application\SpreadhseetBatchCommandAttempts\DownloadFile;

use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\Create\SpreadsheetBatchCommandAttemptCreator;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SpreadsheetBatchCommandAttemptReadRepository;
use Qalis\Shared\Domain\Utils\StringUtils;

final class SpreadsheetBatchCommandAttemptFileDownloader {

    private SpreadsheetBatchCommandAttemptReadRepository $spreadsheetBatchCommandAttemptReadRepository;

    public function __construct(SpreadsheetBatchCommandAttemptReadRepository $spreadsheetBatchCommandAttemptReadRepository)
    {
        $this->spreadsheetBatchCommandAttemptReadRepository = $spreadsheetBatchCommandAttemptReadRepository;
    }

    public function __invoke(string $batchCommandAttemptId)
    {
        $batchCommandAttempt = $this->spreadsheetBatchCommandAttemptReadRepository->find($batchCommandAttemptId);
        $fileName = SpreadsheetBatchCommandAttemptCreator::SPREADSHEET_BATCH_COMMAND_STORAGE_BASE_PATH."/".StringUtils::toSnakeCase($batchCommandAttempt->commandCode()).'/'.$batchCommandAttempt->fileName();
        if (!Storage::exists($fileName)) {
            throw new InvalidArgumentException("No se ha encontrado el archivo <$fileName>");
        }
        return Storage::download($fileName);
    }

}
