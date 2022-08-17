<?php

namespace Qalis\Shared\Application\OrganizationTypes\SearchAll;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

final class SearchAllOrganizationTypesResponse extends CollectionResponse
{

    public function __construct(SearchAllOrganizationTypeResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return SearchAllOrganizationTypeResponse::class;
    }

}
