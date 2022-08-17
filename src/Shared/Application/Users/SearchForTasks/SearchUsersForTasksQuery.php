<?php

namespace Qalis\Shared\Application\Users\SearchForTasks;

use Qalis\Shared\Domain\Bus\Query\Query;

final class SearchUsersForTasksQuery extends Query {

    private string $taskTypeId;

    public function __construct(string $taskTypeId)
    {
        $this->taskTypeId = $taskTypeId;
    }

    /**
     * @return string
     */
    public function taskTypeId(): string
    {
        return $this->taskTypeId;
    }

}
