<?php

namespace Qalis\Shared\Domain\Roles;

use Qalis\Shared\Application\Roles\SearchForTasks\SearchRolesForTasksResponse;

interface RoleReadRepository {
    public function searchForTasks(string $taskTypeId) : SearchRolesForTasksResponse;
}
