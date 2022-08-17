<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SampledOperator extends Model
{
    protected $table = "sampled_operators";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;
}
