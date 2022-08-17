<?php

namespace Qalis\Shared\Application\Users\SearchForTasks;

use Qalis\Shared\Domain\Bus\Query\CollectionResponse;

final class SearchUsersForTasksResponse extends CollectionResponse
{

    public function __construct(SearchUserForTasksResponse ...$items)
    {
        $this->items = $items;
    }

    protected function type(): string
    {
        return SearchUserForTasksResponse::class;
    }

}
