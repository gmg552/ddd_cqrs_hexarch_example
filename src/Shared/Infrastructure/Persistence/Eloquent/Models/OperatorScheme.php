<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorScheme extends Model
{
    protected $table = "operator_schemes";
    use UuidTrait;
    use softDeletes;

    public function operator()
    {
        return $this->belongsTo('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Operator');
    }

    public function scheme()
    {
        return $this->belongsTo('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Scheme');
    }

}
