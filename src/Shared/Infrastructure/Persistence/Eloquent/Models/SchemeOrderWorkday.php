<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeOrderWorkday extends Model
{
    protected $table = "scheme_order_workdays";
    protected $guarded = [];
    use softDeletes;


}
