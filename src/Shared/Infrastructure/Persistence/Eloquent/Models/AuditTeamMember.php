<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditTeamMember extends Model
{
    protected $table = "audit_team_members";
    protected $guarded = []; //Se mete para poder hacer el create []
    use softDeletes;
    use UuidTrait;

    public function auditor() {
        return $this->belongsTo(Auditor::class);
    }

    public function role() {
        return $this->belongsTo(AuditorRole::class);
    }

    public function audit() {
        return $this->belongsTo(Audit::class);
    }
}
