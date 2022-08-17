<?php

namespace Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts;

use Carbon\Carbon;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttempt;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptCode;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptId;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptState;
use Qalis\Shared\Domain\BatchCommands\BatchCommandId;
use Qalis\Shared\Domain\Users\UserId;
use Qalis\Shared\Domain\ValueObjects\DateTimeValueObject;

final class SpreadsheetBatchCommandAttempt extends BatchCommandAttempt {


    private SpreadsheetBatchCommandFileName $fileName;

    public function __construct(
        BatchCommandAttemptId $id,
        BatchCommandId $batchCommandId,
        BatchCommandAttemptState $state,
        DateTimeValueObject $createdAt,
        array $contextParams,
        UserId $userId,
        SpreadsheetBatchCommandFileName  $fileName
    )
    {
        $this->fileName = $fileName;
        parent::__construct($id, $batchCommandId, $state, $createdAt, $contextParams, $userId);
    }

    static public function fromPrimitives(
        string $id,
        string $userId,
        string $batchCommandId,
        array $contextParams,
        string $fileName
    ): SpreadsheetBatchCommandAttempt {
        return new SpreadsheetBatchCommandAttempt(
            new BatchCommandAttemptId($id),
            new BatchCommandId($batchCommandId),
            new BatchCommandAttemptState(BatchCommandAttemptState::PROCESSING),
            new DateTimeValueObject(Carbon::now()->format("Y-m-d h:i:s")),
            $contextParams,
            new UserId($userId),
            new SpreadsheetBatchCommandFileName($fileName)
        );
    }

    /**
     * @return SpreadsheetBatchCommandFileName
     */
    public function fileName(): SpreadsheetBatchCommandFileName
    {
        return $this->fileName;
    }



}
