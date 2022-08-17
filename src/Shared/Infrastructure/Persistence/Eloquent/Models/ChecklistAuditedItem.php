<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistAuditedItem extends Model
{
    protected $table = "checklist_audited_items";
    protected $guarded = [];
    use UuidTrait;
    use softDeletes;

    public function realAuditor() {
        return $this->belongsTo(Auditor::class, 'real_auditor_id');
    }

    public function strawAuditor() {
        return $this->belongsTo(Auditor::class, 'straw_auditor_id');
    }

    public function auditChecklist() {
        return $this->belongsTo(AuditChecklist::class, 'audit_checklist_id');
    }

    public function auditedItem() {
        return $this->belongsTo(AuditedItem::class, 'audited_item_id');
    }

    public function checklistAuditedItemTimestamps() {
        return $this->hasMany(ChecklistAuditedItemTimestamp::class, 'checklist_audited_item_id');
    }

}
