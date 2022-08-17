<?php

namespace Qalis\Shared\Domain\BatchCommandAttempts;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Qalis\Shared\Domain\BatchCommands\BatchCommandId;
use Qalis\Shared\Domain\Users\UserId;
use Qalis\Shared\Domain\ValueObjects\DateTimeValueObject;
use Qalis\Shared\Domain\ValueObjects\Uuid;

class BatchCommandAttempt {

    private BatchCommandAttemptId $id;
    private BatchCommandId $batchCommandId;
    private BatchCommandAttemptState $state;
    private DateTimeValueObject $createdAt;
    private array $contextParams;
    private UserId $userId;

    public function __construct(
        BatchCommandAttemptId $id,
        BatchCommandId $batchCommandId,
        BatchCommandAttemptState $state,
        DateTimeValueObject $createdAt,
        array $contextParams,
        UserId $userId
    )
    {
        $this->id = $id;
        $this->batchCommandId = $batchCommandId;
        $this->state = $state;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
        $this->contextParams = $contextParams;
    }

    /**
     * @return BatchCommandAttemptId
     */
    public function id(): BatchCommandAttemptId
    {
        return $this->id;
    }

    /**
     * @return BatchCommandId
     */
    public function batchCommandId(): BatchCommandId
    {
        return $this->batchCommandId;
    }

    /**
     * @return BatchCommandAttemptState
     */
    public function state(): BatchCommandAttemptState
    {
        return $this->state;
    }

    /**
     * @return DateTimeValueObject
     */
    public function createdAt(): DateTimeValueObject
    {
        return $this->createdAt;
    }

    /**
     * @return array
     */
    public function contextParams(): array
    {
        return $this->contextParams;
    }

    /**
     * @return UserId
     */
    public function userId(): UserId
    {
        return $this->userId;
    }

    /**
     * @param BatchCommandAttemptState $state
     */
    public function updateState(BatchCommandAttemptState $state): void
    {
        $this->state = $state;
    }

}
