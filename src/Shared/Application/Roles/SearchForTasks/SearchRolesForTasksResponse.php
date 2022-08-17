<?php

namespace Qalis\Shared\Application\Roles\SearchForTasks;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

final class SearchRolesForTasksResponse extends CollectionResponse
{

    public function __construct(SearchRoleForTasksResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return SearchRoleForTasksResponse::class;
    }

}
