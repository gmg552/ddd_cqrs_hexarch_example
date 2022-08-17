<?php

namespace Qalis\Certification\Advisors\Application\Search;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

class SearchAdvisorsResponse extends CollectionResponse
{

    public function __construct(SearchAdvisorResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return SearchAdvisorResponse::class;
    }

}
