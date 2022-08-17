<?php

namespace Qalis\Shared\Application\BatchCommandAttempts\Delete;

class DeleteBatchCommandAttemptCommandHandler
{
    private BatchCommandAttemptsRemover $batchCommandAttemptsRemover;

    public function __construct(BatchCommandAttemptsRemover $batchCommandAttemptsRemover)
    {
        $this->batchCommandAttemptsRemover = $batchCommandAttemptsRemover;
    }

    public function __invoke(DeleteBatchCommandAttemptCommand $command)
    {
        $this->batchCommandAttemptsRemover->__invoke($command->id());
    }

}
