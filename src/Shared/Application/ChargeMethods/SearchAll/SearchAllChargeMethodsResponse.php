<?php

namespace Qalis\Shared\Application\ChargeMethods\SearchAll;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

final class SearchAllChargeMethodsResponse extends CollectionResponse
{

    public function __construct(SearchAllChargeMethodResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return SearchAllChargeMethodResponse::class;
    }

}
