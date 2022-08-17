<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\SpreadsheetBatchCommandAttempts;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Application\SpreadhseetBatchCommandAttempts\DownloadFile\SpreadsheetBatchCommandAttemptResponse;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SearchToList\SearchBatchCommandAttemptsToListResponse;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SearchToList\SearchBatchCommandAttemptToListResponse;
use Qalis\Shared\Domain\SpreadsheetBatchCommandAttempts\SpreadsheetBatchCommandAttemptReadRepository;
use stdClass;
use function Lambdish\Phunctional\map;

class LaravelSpreadsheetBatchCommandAttemptReadRepository implements SpreadsheetBatchCommandAttemptReadRepository
{

    public function find(string $batchCommandAttemptId): SpreadsheetBatchCommandAttemptResponse
    {

        $batchCommandAttempt = DB::table('batch_command_attempts')
            ->join('spreadsheet_batch_command_attempts', 'spreadsheet_batch_command_attempts.id', '=', 'batch_command_attempts.id')
            ->join('batch_commands', 'batch_commands.id', '=', 'batch_command_attempts.batch_command_id')
            ->whereRaw('hex(spreadsheet_batch_command_attempts.uuid) = "'.$batchCommandAttemptId.'"')
            ->selectRaw('lower(hex(batch_command_attempts.uuid)) as id, file_name, batch_commands.code')
            ->first();


        return new SpreadsheetBatchCommandAttemptResponse(
            $batchCommandAttempt->id,
            $batchCommandAttempt->file_name,
            $batchCommandAttempt->code
        );
    }

    public function searchToList(string $batchCommandCode, array $contextParams, ?int $limit = null, ?int $offset = null): SearchBatchCommandAttemptsToListResponse
    {
        $attempts = DB::table('batch_command_attempts')
            ->join('spreadsheet_batch_command_attempts', 'spreadsheet_batch_command_attempts.id', '=', 'batch_command_attempts.id')
            ->join('batch_commands', 'batch_commands.id', '=', 'batch_command_attempts.batch_command_id')
            ->where('batch_commands.code', $batchCommandCode)
            ->selectRaw('lower(hex(spreadsheet_batch_command_attempts.uuid)) as id, batch_command_attempts.created_at, batch_command_attempts.state, spreadsheet_batch_command_attempts.file_name')
            ->orderBy('created_at', 'desc');

        $totalCounter = $attempts->count();

        $attempts = $attempts->when($limit !== null, function($query) use ($limit) {
            return $query->limit($limit);
        })
        ->when($offset !== null, function($query) use ($offset) {
            return $query->offset($offset);
        })->get();

        return new SearchBatchCommandAttemptsToListResponse($totalCounter, ...map($this->toResponse(), $attempts));

    }

    private function toResponse(): callable
    {
        return static fn(stdClass $attempt) => new SearchBatchCommandAttemptToListResponse(
            $attempt->id,
            $attempt->created_at,
            $attempt->state,
            $attempt->file_name
        );
    }



}
