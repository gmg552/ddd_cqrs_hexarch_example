<?php

namespace Qalis\Shared\Domain\BatchCommandAttempts;

interface BatchCommandAttemptRepository {
    public function save(BatchCommandAttempt $batchCommandAttempt): void;
    public function find(BatchCommandAttemptId $batchCommandAttemptId): BatchCommandAttempt;
}
