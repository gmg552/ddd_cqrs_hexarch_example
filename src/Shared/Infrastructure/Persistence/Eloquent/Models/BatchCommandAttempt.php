<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchCommandAttempt extends Model
{
    protected $table = "batch_command_attempts";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


}
