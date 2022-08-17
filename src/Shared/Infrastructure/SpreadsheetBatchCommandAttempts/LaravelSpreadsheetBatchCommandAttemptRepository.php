<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\SpreadsheetBatchCommandAttempts;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SpreadsheetBatchCommandAttempt;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SpreadsheetBatchCommandAttemptRepository;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\BatchCommandAttempt as BatchCommandAttemptEloquent;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\SpreadsheetBatchCommandAttempt as SpreadsheetBatchCommandAttemptEloquent;
use Qalis\Shared\Infrastructure\Persistence\Uuid2Id;

class LaravelSpreadsheetBatchCommandAttemptRepository implements SpreadsheetBatchCommandAttemptRepository
{

    public function delete(string $batchCommandAttemptId): void
    {
        $batchCommandAttemptId = Uuid2Id::resolve('batch_command_attempts', $batchCommandAttemptId);

        try {
            DB::beginTransaction();

            DB::table('batch_command_errors')
                ->where('batch_command_attempt_id', $batchCommandAttemptId)
                ->delete();

            DB::table('spreadsheet_batch_command_attempts')
                ->where('id', $batchCommandAttemptId)
                ->delete();

            DB::table('batch_command_attempts')
                ->where('id', $batchCommandAttemptId)
                ->delete();

            DB::commit();
        } catch(\Exception $e){
            DB::rollBack();
            echo 'Error '.$e->getMessage();
        }

    }

    public function save(SpreadsheetBatchCommandAttempt $spreadsheetBatchCommandAttempt): void
    {
        $batchCommandAttempt = BatchCommandAttemptEloquent::updateOrCreate(
            [
                'uuid' => $spreadsheetBatchCommandAttempt->id()->binValue()
            ],
            [
                'batch_command_id' => Uuid2Id::resolve('batch_commands', $spreadsheetBatchCommandAttempt->batchCommandId()->value()),
                'state' => $spreadsheetBatchCommandAttempt->state(),
                'created_at' => $spreadsheetBatchCommandAttempt->createdAt(),
                'context_params' => json_encode($spreadsheetBatchCommandAttempt->contextParams()),
                'user_id' => Uuid2Id::resolve('users', $spreadsheetBatchCommandAttempt->userId()->value()),
            ]
        );

        //Se mete esto, debido a que en la creación no da error, pero cuando haces update del ID, dispara un error. Así evitamos que lo guarde.
        $exist = DB::table('spreadsheet_batch_command_attempts')
            ->whereRaw('hex(spreadsheet_batch_command_attempts.uuid) = "'.$spreadsheetBatchCommandAttempt->id()->value().'"')
            ->exists();

        $idField = $exist ? [] : ['id' => $batchCommandAttempt->id];

        SpreadsheetBatchCommandAttemptEloquent::updateOrCreate(
            [
                'uuid' => $spreadsheetBatchCommandAttempt->id()->binValue()
            ],
            [
                'file_name' => $spreadsheetBatchCommandAttempt->fileName()
            ] + $idField
        );
    }

}
