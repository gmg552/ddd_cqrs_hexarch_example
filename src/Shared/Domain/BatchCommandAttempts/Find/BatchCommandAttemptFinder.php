<?php

namespace Qalis\Shared\Domain\BatchCommandAttempts\Find;

use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptReadRepository;

final class BatchCommandAttemptFinder {

    private BatchCommandAttemptReadRepository $batchCommandAttemptReadRepository;

    public function __construct(BatchCommandAttemptReadRepository $batchCommandAttemptReadRepository)
    {
        $this->batchCommandAttemptReadRepository = $batchCommandAttemptReadRepository;
    }

    public function __invoke(string $batchCommandAttemptId): FindBatchCommandAttemptResponse
    {
        return $this->batchCommandAttemptReadRepository->find($batchCommandAttemptId);
    }

}
