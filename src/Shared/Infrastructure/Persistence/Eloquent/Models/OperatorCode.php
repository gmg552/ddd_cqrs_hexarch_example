<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorCode extends Model
{
    protected $table = "operator_codes";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


}
