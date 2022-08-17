<?php

namespace Qalis\Shared\Application\Roles\SearchForTasks;

final class SearchRolesForTasksQueryHandler
{
    private RolesForTasksSearcher $rolesForTasksSearcher;

    public function __construct(RolesForTasksSearcher $rolesForTasksSearcher)
    {
        $this->rolesForTasksSearcher = $rolesForTasksSearcher;
    }

    public function __invoke(SearchRolesForTasksQuery $query): SearchRolesForTasksResponse
    {
        return $this->rolesForTasksSearcher->__invoke($query->taskTypeId());
    }
}
