<?php

namespace Qalis\Shared\Application\Cities\SearchByProvince;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

final class SearchCitiesResponse extends CollectionResponse
{

    public function __construct(SearchCityResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return SearchCityResponse::class;
    }

}
