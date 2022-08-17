<?php

namespace Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts;

interface SpreadsheetBatchCommandAttemptRepository {
    public function save(SpreadsheetBatchCommandAttempt $spreadsheetBatchCommandAttempt): void;
    public function delete(string $batchCommandAttemptId): void;
}
