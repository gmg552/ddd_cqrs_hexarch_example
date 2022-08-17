<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditChecklistScheme extends EloquentEntity
{
    protected $table = "audit_checklist_schemes";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function scheme() {
        return $this->belongsTo(Scheme::class);
    }

    public function checklist() {
        return $this->belongsTo(AuditChecklist::class);
    }


}
