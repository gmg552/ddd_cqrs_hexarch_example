<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchCommandError extends Model
{
    protected $table = "batch_command_errors";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


}
