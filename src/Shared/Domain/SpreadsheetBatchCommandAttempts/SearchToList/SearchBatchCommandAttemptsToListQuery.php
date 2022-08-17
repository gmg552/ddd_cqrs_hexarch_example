<?php

namespace Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SearchToList;

use Qalis\Shared\Domain\Bus\Query\Query;

final class SearchBatchCommandAttemptsToListQuery extends Query {

    private string $batchCommandCode;
    private array $contextParams;
    private ?int $limit;
    private ?int $offset;

    public function __construct(
        string $batchCommandCode,
        array $contextParams,
        ?int $limit = null,
        ?int $offset = null
    )
    {
        $this->batchCommandCode = $batchCommandCode;
        $this->contextParams = $contextParams;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @return string
     */
    public function batchCommandCode(): string
    {
        return $this->batchCommandCode;
    }

    /**
     * @return array
     */
    public function contextParams(): array
    {
        return $this->contextParams;
    }

    /**
     * @return int|null
     */
    public function limit(): ?int
    {
        return $this->limit;
    }

    /**
     * @return int|null
     */
    public function offset(): ?int
    {
        return $this->offset;
    }

}
