<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeOrderCommitment extends Model
{
    protected $table = "scheme_order_commitments";
    protected $guarded = [];
    use softDeletes;

}
