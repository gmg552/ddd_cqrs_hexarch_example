<?php

namespace Qalis\Shared\Domain\BatchCommandAttempts\Find;

use Qalis\Shared\Domain\Bus\Query\Query;

class FindBatchCommandAttemptQuery extends Query {

    private string $batchCommandAttemptId;

    public function __construct(
        string $batchCommandAttemptId
    )
    {
        $this->batchCommandAttemptId = $batchCommandAttemptId;
    }

    /**
     * @return string
     */
    public function batchCommandAttemptId(): string
    {
        return $this->batchCommandAttemptId;
    }

}
