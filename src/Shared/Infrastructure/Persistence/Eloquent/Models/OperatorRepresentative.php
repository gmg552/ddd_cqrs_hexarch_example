<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperatorRepresentative extends Model
{
    protected $table = "audit_operator_representatives";
    protected $guarded = []; //Se mete para poder hacer el create []
    use softDeletes;
    use UuidTrait;

    public function audit(){
        return $this->belongsTo(Audit::class);
    }

}
