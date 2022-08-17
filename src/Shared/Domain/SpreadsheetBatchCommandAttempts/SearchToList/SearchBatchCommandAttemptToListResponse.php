<?php

namespace Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SearchToList;

final class SearchBatchCommandAttemptToListResponse {

    private string $id;
    private string $createdAt;
    private string $state;

    public function __construct(
        string $id,
        string $createdAt,
        string $state
    )
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->state = $state;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'createdAt' => $this->createdAt,
            'state' => $this->state
        ];
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function createdAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function state(): string
    {
        return $this->state;
    }

}
