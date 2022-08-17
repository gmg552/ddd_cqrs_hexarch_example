<?php

namespace Qalis\Shared\Domain\BatchCommandErrors;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

class BatchCommandErrorsResponse extends CollectionResponse
{

    public function __construct(BatchCommandErrorResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return BatchCommandErrorResponse::class;
    }

}


