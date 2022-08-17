<?php

namespace Qalis\Shared\Application\Users\SearchForTasks;

final class SearchUsersForTasksQueryHandler
{
    private UsersForTasksSearcher $usersForTasksSearcher;

    public function __construct(UsersForTasksSearcher $usersForTasksSearcher)
    {
        $this->usersForTasksSearcher = $usersForTasksSearcher;
    }

    public function __invoke(SearchUsersForTasksQuery $query): SearchUsersForTasksResponse
    {
        return $this->usersForTasksSearcher->__invoke($query->taskTypeId());
    }
}
