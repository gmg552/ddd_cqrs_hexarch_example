<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeOperatorCodeRange extends Model
{
    protected $table = "scheme_operator_code_ranges";
    protected $guarded = [];
    use softDeletes;

    public function scheme()
    {
        return $this->belongsTo('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Scheme');
    }

    public function range()
    {
        return $this->belongsTo('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\OperatorCodeRange');
    }

}
