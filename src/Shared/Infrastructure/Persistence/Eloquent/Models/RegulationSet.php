<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegulationSet extends Model
{
    protected $table = "regulation_sets";
    use softDeletes;
    use UuidTrait;


}
