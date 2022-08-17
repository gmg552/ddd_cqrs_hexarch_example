<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SampledHandlingUnit extends Model
{
    protected $table = "sampled_handling_units";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;
}
