<?php

namespace Qalis\Shared\Domain\BatchCommandAttempts\Find;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

class FindBatchCommandErrorsResponse extends CollectionResponse
{

    public function __construct(FindBatchCommandErrorResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return FindBatchCommandErrorResponse::class;
    }

}


