<?php

namespace Qalis\Shared\Domain\BatchCommandErrors;

use Carbon\Carbon;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptId;
use Qalis\Shared\Domain\ValueObjects\DateTimeValueObject;
use Qalis\Shared\Domain\ValueObjects\PositiveIntValueObject;

final class BatchCommandError {

    private BatchCommandErrorId $id;
    private BatchCommandAttemptId $batchCommandAttemptId;
    private DateTimeValueObject $createdAt;
    private BatchCommandErrorMessage $message;
    private ?PositiveIntValueObject $index;
    private ?BatchCommandErrorHowToFix $howToFix;

    public function __construct(
        BatchCommandErrorId $id,
        BatchCommandAttemptId $batchCommandAttemptId,
        DateTimeValueObject $createdAt,
        BatchCommandErrorMessage $message,
        ?PositiveIntValueObject $index = null,
        ?BatchCommandErrorHowToFix $howToFix = null
    )
    {
        $this->id = $id;
        $this->batchCommandAttemptId = $batchCommandAttemptId;
        $this->createdAt = $createdAt;
        $this->index = $index;
        $this->message = $message;
        $this->howToFix = $howToFix;
    }

    public static function fromPrimitives(
        string $id,
        string $batchCommandAttemptId,
        string $message,
        ?int $index = null,
        ?string $howToFix = null
    ): BatchCommandError {
        return new BatchCommandError(
            new BatchCommandErrorId($id),
            new BatchCommandAttemptId($batchCommandAttemptId),
            new DateTimeValueObject(Carbon::now()->format("Y-m-d")),
            new BatchCommandErrorMessage($message),
            $index ? new PositiveIntValueObject($index) : null,
            $howToFix ? new BatchCommandErrorHowToFix($howToFix) : null
        );
    }

    /**
     * @return BatchCommandErrorId
     */
    public function id(): BatchCommandErrorId
    {
        return $this->id;
    }

    /**
     * @return BatchCommandAttemptId
     */
    public function batchCommandAttemptId(): BatchCommandAttemptId
    {
        return $this->batchCommandAttemptId;
    }

    /**
     * @return DateTimeValueObject
     */
    public function createdAt(): DateTimeValueObject
    {
        return $this->createdAt;
    }


    /**
     * @return BatchCommandErrorMessage
     */
    public function message(): BatchCommandErrorMessage
    {
        return $this->message;
    }

    /**
     * @return BatchCommandErrorHowToFix|null
     */
    public function howToFix(): ?BatchCommandErrorHowToFix
    {
        return $this->howToFix;
    }

    /**
     * @return PositiveIntValueObject|null
     */
    public function index(): ?PositiveIntValueObject
    {
        return $this->index;
    }



}
