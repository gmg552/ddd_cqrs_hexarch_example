<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\BatchCommandAttempts;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptReadRepository;
use Qalis\Shared\Domain\BatchCommandAttempts\Find\FindBatchCommandAttemptResponse;
use Qalis\Shared\Domain\BatchCommandAttempts\Find\FindBatchCommandErrorResponse;
use Qalis\Shared\Domain\BatchCommandAttempts\Find\FindBatchCommandErrorsResponse;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;

class LaravelBatchCommandAttemptReadRepository implements BatchCommandAttemptReadRepository
{

    public function find(string $batchCommandAttemptId): FindBatchCommandAttemptResponse
    {
        $batchCommandAttempt = QueryBuilderUtils::notDeleted(DB::table('batch_command_attempts')
            ->join('batch_commands', 'batch_commands.id', '=', 'batch_command_attempts.batch_command_id')
            ->join('users', 'users.id', '=', 'batch_command_attempts.user_id'))
            ->selectRaw('lower(hex(batch_command_attempts.uuid)) as id, lower(hex(batch_commands.uuid)) as batchCommandId, batch_command_attempts.created_at, batch_command_attempts.context_params, batch_command_attempts.state, lower(hex(users.uuid)) as userId')
            ->whereRaw('hex(batch_command_attempts.uuid) = "'.$batchCommandAttemptId.'"')
            ->first();

        $batchCommandErrors = QueryBuilderUtils::notDeleted(DB::table('batch_command_errors')
            ->join('batch_command_attempts', 'batch_command_attempts.id', '=', 'batch_command_errors.batch_command_attempt_id'))
            ->whereRaw('hex(batch_command_attempts.uuid) = "'.$batchCommandAttemptId.'"')
            ->selectRaw('batch_command_errors.index, message, how_to_fix')
            ->get();

        $errors = new FindBatchCommandErrorsResponse();

        foreach($batchCommandErrors as $batchCommandError) {
            $errors->add(new FindBatchCommandErrorResponse(
                $batchCommandError->index,
                $batchCommandError->message,
                $batchCommandError->how_to_fix
            ));
        }

        return new FindBatchCommandAttemptResponse(
            $batchCommandAttempt->id,
            $batchCommandAttempt->created_at,
            $errors
        );
    }

}
