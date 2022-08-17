<?php

namespace Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\GetFileLink;

use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\Create\SpreadsheetBatchCommandAttemptCreator;
use Qalis\Shared\Domain\Storage\StorageProvider;
use Qalis\Shared\Domain\Utils\StringUtils;

final class SpreadsheetBatchCommandAttemptFileLinkGetter {

    private StorageProvider $storageProvider;

    public function __construct(StorageProvider $storageProvider)
    {
        $this->storageProvider = $storageProvider;
    }

    public function __invoke(string $fileName, string $commandCode): string
    {
        return $this->storageProvider->link(SpreadsheetBatchCommandAttemptCreator::SPREADSHEET_BATCH_COMMAND_STORAGE_BASE_PATH.'/'.StringUtils::toSnakeCase($commandCode).'/'.$fileName);
    }

}
