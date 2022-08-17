<?php

namespace Qalis\Shared\Application\Users\SearchForTasks;

use Qalis\Shared\Domain\Users\UserReadRepository;

final class UsersForTasksSearcher {

    private UserReadRepository $userReadRepository;

    public function __construct(UserReadRepository $userReadRepository)
    {
        $this->userReadRepository = $userReadRepository;
    }

    public function __invoke(string $taskTypeId): SearchUsersForTasksResponse
    {
        return $this->userReadRepository->searchForTasks($taskTypeId);
    }

}
