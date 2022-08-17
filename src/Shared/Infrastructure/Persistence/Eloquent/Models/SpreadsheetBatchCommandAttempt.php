<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpreadsheetBatchCommandAttempt extends Model
{
    protected $table = "spreadsheet_batch_command_attempts";
    protected $guarded = [];
    use UuidTrait;


}
