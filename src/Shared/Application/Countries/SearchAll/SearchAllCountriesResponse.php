<?php

namespace Qalis\Shared\Application\Countries\SearchAll;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

final class SearchAllCountriesResponse extends CollectionResponse
{

    public function __construct(SearchAllCountryResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return SearchAllCountryResponse::class;
    }

}
