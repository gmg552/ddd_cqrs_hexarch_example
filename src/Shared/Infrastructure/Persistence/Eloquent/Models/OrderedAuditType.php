<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderedAuditType extends Model
{
    protected $table = "ordered_audit_types";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function orderedScheme()
    {
        return $this->belongsTo('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\OrderedScheme');
    }

    public function auditType(){
        return $this->belongsTo('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\AuditType');
    }

}
