<?php

namespace Qalis\Shared\Application\SpreadsheetBatchCommands\Search;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

final class SearchSpreadsheetBatchCommandsResponse extends CollectionResponse
{

    public function __construct(SearchSpreadsheetBatchCommandResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return SearchSpreadsheetBatchCommandResponse::class;
    }

}
