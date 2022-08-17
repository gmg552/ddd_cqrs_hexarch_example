<?php

namespace Qalis\Shared\Application\Provinces\SearchByCountry;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

final class SearchProvincesResponse extends CollectionResponse
{

    public function __construct(SearchProvinceResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return SearchProvinceResponse::class;
    }

}
