<?php

namespace Qalis\Shared\Domain\Users;

use Qalis\Shared\Application\Users\SearchForTasks\SearchUsersForTasksResponse;

interface UserReadRepository {
    public function findAuthenticated(): UserResponse;
    public function searchForTasks(string $taskTypeId) : SearchUsersForTasksResponse;
}
