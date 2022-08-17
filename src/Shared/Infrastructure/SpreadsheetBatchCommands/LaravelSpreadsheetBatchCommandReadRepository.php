<?php

declare(strict_types=1);

namespace Qalis\Shared\Infrastructure\SpreadsheetBatchCommands;

use Illuminate\Support\Facades\DB;
use Qalis\Shared\Application\SpreadsheetBatchCommands\FindToRun\SpreadsheetBatchCommandToRunResponse;
use Qalis\Shared\Application\SpreadsheetBatchCommands\Search\SearchSpreadsheetBatchCommandResponse;
use Qalis\Shared\Application\SpreadsheetBatchCommands\Search\SearchSpreadsheetBatchCommandsResponse;
use Qalis\Shared\Domain\SpreadsheetBatchCommands\SpreadsheetBatchCommandReadRepository;
use Qalis\Shared\Infrastructure\Persistence\QueryBuilder\QueryBuilderUtils;
use stdClass;
use function Lambdish\Phunctional\map;

class LaravelSpreadsheetBatchCommandReadRepository implements SpreadsheetBatchCommandReadRepository
{

    public function findToRun(string $spreadsheetFormatId): SpreadsheetBatchCommandToRunResponse
    {
        $spreadsheetFormat = QueryBuilderUtils::notDeleted(DB::table('batch_commands')
            ->join('spreadsheet_batch_commands', 'spreadsheet_batch_commands.batch_command_id', '=', 'batch_commands.id')
            ->join('spreadsheet_formats', 'spreadsheet_batch_commands.spreadsheet_format_id', '=', 'spreadsheet_formats.id'))
            ->whereRaw('hex(spreadsheet_formats.uuid) = "'.$spreadsheetFormatId.'"')
            ->selectRaw('lower(hex(batch_commands.uuid)) as batchCommandId, spreadsheet_batch_commands.param_matching, spreadsheet_batch_commands.custom_field_param_matching, spreadsheet_formats.id, spreadsheet_formats.sheet_name, first_row, protocol, batch_commands.code')
            ->first();

        $columnIndexes = QueryBuilderUtils::notDeleted(DB::table('spreadsheet_columns'))
            ->where('spreadsheet_format_id', $spreadsheetFormat->id)
            ->select('code', 'index')
            ->pluck('index', 'code')
            ->toArray();

        return new SpreadsheetBatchCommandToRunResponse(
            $spreadsheetFormat->batchCommandId,
            $spreadsheetFormat->sheet_name,
            $spreadsheetFormat->first_row,
            $columnIndexes,
            $spreadsheetFormat->protocol,
            $spreadsheetFormat->code,
            $spreadsheetFormat->param_matching ? json_decode($spreadsheetFormat->param_matching, true) : [],
            $spreadsheetFormat->custom_field_param_matching ? json_decode($spreadsheetFormat->custom_field_param_matching, true) : []
        );

    }

    public function search(string $batchCommandCode): SearchSpreadsheetBatchCommandsResponse
    {

        $spreadsheetFormats = QueryBuilderUtils::notDeleted(DB::table('batch_commands')
            ->join('spreadsheet_batch_commands', 'spreadsheet_batch_commands.batch_command_id', '=', 'batch_commands.id')
            ->join('spreadsheet_formats', 'spreadsheet_batch_commands.spreadsheet_format_id', '=', 'spreadsheet_formats.id'))
            ->where('batch_commands.code', $batchCommandCode)
            ->selectRaw('lower(hex(spreadsheet_formats.uuid)) as id, spreadsheet_formats.name')
            ->get();


        return new SearchSpreadsheetBatchCommandsResponse(...map($this->toResponse(), $spreadsheetFormats));

    }

    private function toResponse(): callable
    {
        return static fn(stdClass $spreadsheetFormat) => new SearchSpreadsheetBatchCommandResponse(
            $spreadsheetFormat->id,
            $spreadsheetFormat->name
        );
    }

}
