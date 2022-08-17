<?php

namespace Qalis\Shared\Domain\BatchCommandAttempts\Find;

use Qalis\Shared\Domain\Bus\Query\Query;

class FindBatchCommandAttemptQueryHandler {

    private BatchCommandAttemptFinder $batchCommandAttemptFinder;

    public function __construct(BatchCommandAttemptFinder $batchCommandAttemptFinder)
    {
        $this->batchCommandAttemptFinder = $batchCommandAttemptFinder;
    }

    public function __invoke(FindBatchCommandAttemptQuery $query): FindBatchCommandAttemptResponse
    {
        return $this->batchCommandAttemptFinder->__invoke($query->batchCommandAttemptId());
    }

}
