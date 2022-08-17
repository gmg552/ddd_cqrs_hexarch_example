<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\BatchCommandErrors;

use Qalis\Shared\Domain\BatchCommandAttempts\Find\FindBatchCommandErrorsResponse;
use Qalis\Shared\Domain\BatchCommandErrors\BatchCommandErrorReadRepository;

final class LaravelBatchCommandErrorReadRepository implements BatchCommandErrorReadRepository {

    public function searchByBatchCommandAttempt(string $batchCommandAttemptId): FindBatchCommandErrorsResponse
    {
        // TODO: Implement searchByBatchCommandAttempt() method.
    }

}
