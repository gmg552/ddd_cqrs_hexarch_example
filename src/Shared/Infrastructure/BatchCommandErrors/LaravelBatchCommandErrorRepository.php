<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\BatchCommandErrors;

use Qalis\Shared\Domain\BatchCommandErrors\BatchCommandError;
use Qalis\Shared\Domain\BatchCommandErrors\BatchCommandErrorRepository;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\BatchCommandError as BatchCommandErrorEloquent;
use Qalis\Shared\Infrastructure\Persistence\Uuid2Id;

final class LaravelBatchCommandErrorRepository implements BatchCommandErrorRepository {

    public function save(BatchCommandError $batchCommandError): void
    {
        BatchCommandErrorEloquent::updateOrCreate(
            [
                'uuid' => $batchCommandError->id()->binValue()
            ],
            [
                'batch_command_attempt_id' => Uuid2Id::resolve('batch_command_attempts', $batchCommandError->batchCommandAttemptId()->value()),
                'created_at' => $batchCommandError->createdAt()->__toString(),
                'index' => $batchCommandError->index() ? $batchCommandError->index()->value() : null,
                'message' => $batchCommandError->message()->value(),
                'how_to_fix' => $batchCommandError->howToFix() ? $batchCommandError->howToFix()->value() : null
            ]
        );
    }

}
