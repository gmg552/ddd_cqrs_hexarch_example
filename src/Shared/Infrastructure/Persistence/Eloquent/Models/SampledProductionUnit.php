<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SampledProductionUnit extends Model
{
    protected $table = "sampled_production_units";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;
}
