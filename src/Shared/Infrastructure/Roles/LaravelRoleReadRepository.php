<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Roles;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Application\Roles\SearchForTasks\SearchRoleForTasksResponse;
use Qalis\Shared\Application\Roles\SearchForTasks\SearchRolesForTasksResponse;
use Qalis\Shared\Domain\Roles\RoleReadRepository;
use stdClass;
use function Lambdish\Phunctional\map;

class LaravelRoleReadRepository implements RoleReadRepository {

    public function searchForTasks(string $taskTypeId): SearchRolesForTasksResponse
    {
        $users = DB::table('task_type_roles')
            ->join('task_types', 'task_type_roles.task_type_id', '=', 'task_types.id')
            ->join('roles', 'task_type_roles.role_id', '=', 'roles.id')
            ->whereRaw('hex(task_types.uuid) = "'.$taskTypeId.'"')
            ->selectRaw('lower(hex(roles.uuid)) as roleId, roles.name')
            ->get();

        return new SearchRolesForTasksResponse(...map($this->toResponse(), $users));
    }

    private function toResponse(): callable
    {
        return static fn(stdClass $user) => new SearchRoleForTasksResponse(
            $user->roleId,
            $user->name
        );
    }

}
