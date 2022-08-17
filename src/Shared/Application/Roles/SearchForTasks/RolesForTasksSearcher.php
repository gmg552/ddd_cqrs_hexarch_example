<?php

namespace Qalis\Shared\Application\Roles\SearchForTasks;

use Qalis\Shared\Domain\Roles\RoleReadRepository;

final class RolesForTasksSearcher {

    private RoleReadRepository $userReadRepository;

    public function __construct(RoleReadRepository $userReadRepository)
    {
        $this->userReadRepository = $userReadRepository;
    }

    public function __invoke(string $taskTypeId): SearchRolesForTasksResponse
    {
        return $this->userReadRepository->searchForTasks($taskTypeId);
    }

}
