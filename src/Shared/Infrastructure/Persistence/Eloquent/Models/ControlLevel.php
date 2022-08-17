<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlLevel extends Model
{
    protected $table = "audit_control_level_types";
    use softDeletes;
    use UuidTrait;

    public function auditTypes() {
        return $this->belongsToMany(AuditType::class, 'audit_type_control_level_types', 'audit_control_level_type_id', 'audit_type_id');
    }


}
