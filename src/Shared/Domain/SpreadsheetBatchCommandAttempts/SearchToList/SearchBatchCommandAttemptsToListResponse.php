<?php

namespace Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SearchToList;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

class SearchBatchCommandAttemptsToListResponse extends CollectionResponse {

    private ?int $totalRecords;

    public function __construct(?int $totalRecords = null, SearchBatchCommandAttemptToListResponse ...$items)
    {
        $this->items = $items;
        $this->totalRecords = $totalRecords;
    }

    protected function type(): string
    {
        return SearchBatchCommandAttemptToListResponse::class;
    }

    public function toArray(): array
    {
        $items = parent::toArray();
        return [
            'totalRecords' => $this->totalRecords,
            'items' => $items
        ];
    }

}
