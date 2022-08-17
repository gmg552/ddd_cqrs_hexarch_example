<?php

namespace Qalis\Shared\Domain\Excel\ReadDataTable;

interface ExcelDataTableReader {
    public function __invoke(string $filePath, string $sheetName, int $firstRow, array $columnIndexes): array;
}
