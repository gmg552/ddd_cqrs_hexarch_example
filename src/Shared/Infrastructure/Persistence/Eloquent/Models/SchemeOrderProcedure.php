<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeOrderProcedure extends Model
{
    protected $table = "scheme_order_procedures";
    protected $guarded = [];
    use softDeletes;

}
