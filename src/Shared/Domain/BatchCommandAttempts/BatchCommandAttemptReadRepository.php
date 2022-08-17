<?php

namespace Qalis\Shared\Domain\BatchCommandAttempts;

use Qalis\Shared\Domain\BatchCommandAttempts\Find\FindBatchCommandAttemptResponse;

interface BatchCommandAttemptReadRepository {
    public function find(string $batchCommandAttemptId): FindBatchCommandAttemptResponse;
}
