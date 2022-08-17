<?php

namespace Qalis\Certification\Audits\Domain\CheckTimestampOverlaps;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

class CheckTimestampOverlapsResponse extends CollectionResponse
{
    public function __construct(CheckTimestampOverlapResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return CheckTimestampOverlapResponse::class;
    }

}
