<?php

namespace Qalis\Shared\Application\PrivacyLevels\SearchByScheme;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

final class SearchPrivacyLevelsResponse extends CollectionResponse
{

    public function __construct(SearchPrivacyLevelResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return SearchPrivacyLevelResponse::class;
    }

}
