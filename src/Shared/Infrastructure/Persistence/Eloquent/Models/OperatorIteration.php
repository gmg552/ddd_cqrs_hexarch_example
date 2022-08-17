<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorIteration extends Model
{
    protected $table = "operator_iterations";
    protected $guarded = [];
    public $incrementing = false;
    use softDeletes;
    use UuidTrait;

    public function iteration() {
        return $this->belongsTo(Iteration::class, 'id', 'id');
    }

    public function iteratedOperators() {
        return $this->hasMany(IteratedOperator::class, 'operator_iteration_id');
    }

}
