<?php

namespace Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\Create;

use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SpreadsheetBatchCommandAttempt;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SpreadsheetBatchCommandAttemptRepository;
use Qalis\Shared\Domain\Storage\StorageProvider;
use Qalis\Shared\Domain\Utils;
use Qalis\Shared\Domain\Utils\StringUtils;
use Qalis\Shared\Utils\FileUtils;

final class SpreadsheetBatchCommandAttemptCreator {

    public const SPREADSHEET_BATCH_COMMAND_STORAGE_BASE_PATH = 'spreadsheet_batch_commands';

    private SpreadsheetBatchCommandAttemptRepository $spreadsheetBatchCommandAttemptRepository;
    private StorageProvider $storageProvider;

    public function __construct(
        SpreadsheetBatchCommandAttemptRepository $spreadsheetBatchCommandAttemptRepository,
        StorageProvider $storageProvider
    )
    {
        $this->spreadsheetBatchCommandAttemptRepository = $spreadsheetBatchCommandAttemptRepository;
        $this->storageProvider = $storageProvider;
    }

    public function __invoke(string $batchCommandId, string $id, string $commandCode, string $userId, string $fileContent, string $originalFileName, array $contextParams): void
    {
        $extension = FileUtils::getExtensionFromPath($originalFileName);
        $fileName = $id.".".$extension;

        $spreadsheetBatchCommandAttempt = SpreadsheetBatchCommandAttempt::fromPrimitives(
            $id, $userId, $batchCommandId, $contextParams, $fileName
        );

        $this->spreadsheetBatchCommandAttemptRepository->save($spreadsheetBatchCommandAttempt);

        $this->storageProvider->put(self::SPREADSHEET_BATCH_COMMAND_STORAGE_BASE_PATH."/".StringUtils::toSnakeCase($commandCode), $fileName, $fileContent);
    }


}
