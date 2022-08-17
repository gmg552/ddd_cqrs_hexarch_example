<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeOrderProcedureType extends Model
{
    protected $table = "scheme_order_procedure_types";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;



}
