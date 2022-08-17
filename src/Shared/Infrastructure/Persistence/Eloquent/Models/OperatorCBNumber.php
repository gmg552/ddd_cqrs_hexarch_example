<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorCBNumber extends Model
{
    protected $table = "operator_cb_numbers";
    protected $guarded = [];
    use UuidTrait;
    use softDeletes;

    public function operator() {
        return $this->belongsTo(Operator::class);
    }

    public function operatorCBNumerationRange() {
        return $this->belongsTo(OperatorCBNumerationRange::class, 'operator_cb_numeration_range_id');
    }

}
