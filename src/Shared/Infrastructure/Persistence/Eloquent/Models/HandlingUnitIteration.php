<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HandlingUnitIteration extends Model
{
    protected $table = "handling_unit_iterations";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function iteration() {
        return $this->belongsTo(Iteration::class, 'id', 'id');
    }

}
