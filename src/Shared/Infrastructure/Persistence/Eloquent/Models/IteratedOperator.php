<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IteratedOperator extends Model
{
    protected $table = "iterated_operators";
    protected $guarded = [];
    use softDeletes;

    public function privacyLevel(){
        return $this->belongsTo(PrivacyLevel::class, 'privacy_level_id');
    }

    public function operatorIteration(){
        return $this->belongsTo(OperatorIteration::class, 'operator_iteration_id');
    }

    public function operator(){
        return $this->belongsTo(Operator::class, 'operator_id');
    }

}
