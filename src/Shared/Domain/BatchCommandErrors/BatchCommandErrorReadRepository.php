<?php

namespace Qalis\Shared\Domain\BatchCommandErrors;

use Qalis\Shared\Domain\BatchCommandAttempts\Find\FindBatchCommandErrorsResponse;

interface BatchCommandErrorReadRepository {
    public function searchByBatchCommandAttempt(string $batchCommandAttemptId) : FindBatchCommandErrorsResponse;
}
