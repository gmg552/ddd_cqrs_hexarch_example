<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeOrderCommitmentType extends Model
{
    protected $table = "scheme_order_commitment_types";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

}
