<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\BatchCommandAttempts;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttempt;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptCode;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptId;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptRepository;
use Qalis\Shared\Domain\BatchCommandAttempts\BatchCommandAttemptState;
use Qalis\Shared\Domain\BatchCommands\BatchCommandId;
use Qalis\Shared\Domain\Users\UserId;
use Qalis\Shared\Domain\ValueObjects\DateTimeValueObject;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\BatchCommandAttempt as BatchCommandAttemptEloquent;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use Qalis\Shared\Infrastructure\Persistence\Uuid2Id;

class LaravelBatchCommandAttemptRepository implements BatchCommandAttemptRepository
{

    public function find(BatchCommandAttemptId $batchCommandAttemptId): BatchCommandAttempt
    {

        $batchCommandAttempt = QueryBuilderUtils::notDeleted(DB::table('batch_command_attempts')
            ->join('batch_commands', 'batch_commands.id', '=', 'batch_command_attempts.batch_command_id')
            ->join('users', 'users.id', '=', 'batch_command_attempts.user_id'))
            ->selectRaw('lower(hex(batch_command_attempts.uuid)) as id, lower(hex(batch_commands.uuid)) as batchCommandId, batch_command_attempts.created_at, batch_command_attempts.context_params, batch_command_attempts.state, lower(hex(users.uuid)) as userId')
            ->whereRaw('hex(batch_command_attempts.uuid) = "'.$batchCommandAttemptId->value().'"')
            ->first();

        return new BatchCommandAttempt(
            new BatchCommandAttemptId($batchCommandAttempt->id),
            new BatchCommandId($batchCommandAttempt->batchCommandId),
            new BatchCommandAttemptState($batchCommandAttempt->state),
            new DateTimeValueObject($batchCommandAttempt->created_at),
            json_decode($batchCommandAttempt->context_params, true),
            new UserId($batchCommandAttempt->userId)
        );

    }

    public function save(BatchCommandAttempt $batchCommandAttempt): void
    {

        BatchCommandAttemptEloquent::updateOrCreate(
            [
                'uuid' => $batchCommandAttempt->id()->binValue()
            ],
            [
                'context_params' => json_encode($batchCommandAttempt->contextParams()),
                'created_at' => $batchCommandAttempt->createdAt(),
                'batch_command_id' => Uuid2Id::resolve('batch_commands', $batchCommandAttempt->batchCommandId()->value()),
                'state' => $batchCommandAttempt->state()->value(),
                'user_id' => Uuid2Id::resolve('users', $batchCommandAttempt->userId()->value())
            ]
        );

    }

}
