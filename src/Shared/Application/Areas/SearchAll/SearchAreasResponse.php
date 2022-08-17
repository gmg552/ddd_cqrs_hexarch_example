<?php

namespace Qalis\Shared\Application\Areas\SearchAll;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

class SearchAreasResponse extends CollectionResponse {

    public function __construct(SearchAreaResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return SearchAreaResponse::class;
    }

}
