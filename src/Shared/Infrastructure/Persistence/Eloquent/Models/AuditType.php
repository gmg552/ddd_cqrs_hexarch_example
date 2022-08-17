<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditType extends Model
{
    protected $table = "audit_types";
    use UuidTrait;
    use softDeletes;

    public function controlLevels() {
        return $this->belongsToMany('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\ControlLevel', 'audit_type_control_level_types', 'audit_type_id', 'audit_control_level_type_id');
    }

    public function schemes() {
        return $this->belongsToMany(Scheme::class, SchemeAuditType::class);
    }

}
