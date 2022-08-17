<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\Users;

use InvalidArgumentException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Qalis\Shared\Application\Users\SearchForTasks\SearchUserForTasksResponse;
use Qalis\Shared\Application\Users\SearchForTasks\SearchUsersForTasksResponse;
use Qalis\Shared\Domain\TaskTypeUsers\TaskTypeUser;
use Qalis\Shared\Domain\Users\UserReadRepository;
use Qalis\Shared\Domain\Users\UserResponse;
use stdClass;
use function Lambdish\Phunctional\map;

class LaravelUserReadRepository implements UserReadRepository {

    public function searchForTasks(string $taskTypeId): SearchUsersForTasksResponse
    {
        $users = DB::table('task_type_users')
            ->join('task_types', 'task_type_users.task_type_id', '=', 'task_types.id')
            ->join('users', 'task_type_users.user_id', '=', 'users.id')
            ->whereRaw('hex(task_types.uuid) = "'.$taskTypeId.'"')
            ->where('as', TaskTypeUser::TAKER)
            ->selectRaw('lower(hex(users.uuid)) as userId, users.name')
            ->get();

        return new SearchUsersForTasksResponse(...map($this->toResponse(), $users));
    }

    private function toResponse(): callable
    {
        return static fn(stdClass $user) => new SearchUserForTasksResponse(
            $user->userId,
            $user->name
        );
    }

    public function findAuthenticated(): UserResponse
    {
        if (!Auth::user()) {
            throw new InvalidArgumentException('No existe un usuario logueado en el sistema.');
        }

        $user = DB::table('users')
            ->join('subjects', 'subjects.id', '=', 'users.subject_id')
            ->where('users.id', Auth::user()->id)
            ->selectRaw('lower(hex(users.uuid)) as id, subjects.name, users.email, subjects.surname1, subjects.surname2')
            ->first();

        return new UserResponse(
            $user->id,
            $user->name,
            $user->email,
            $user->surname1,
            $user->surname2
        );

    }

}
