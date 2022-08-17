<?php

namespace Qalis\Shared\Domain\BatchCommandErrors;

interface BatchCommandErrorRepository {
    public function save(BatchCommandError $batchCommandError): void;
}
