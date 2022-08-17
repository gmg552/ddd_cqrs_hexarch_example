<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditChecklist extends Model
{
    protected $table = "audit_checklists";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function version() {
        return $this->belongsTo(ChecklistVersion::class, 'checklist_version_id');
    }

    public function chiefAuditor() {
        return $this->belongsTo(Auditor::class, 'chief_auditor_id');
    }

    public function strawAuditor() {
        return $this->belongsTo(Auditor::class, 'straw_auditor_id');
    }

    public function audit() {
        return $this->belongsTo(Audit::class, 'audit_id');
    }

    public function operator() {
        return $this->belongsTo(Subject::class, 'operator_id');
    }

    public function reviewer() {
        return $this->belongsTo(Auditor::class, 'reviewer_id');
    }

    public function encodedTerms() {
        return $this->hasMany(ChecklistEncodedTerm::class, 'audit_checklist_id');
    }

    public function auditors() {
        return $this->belongsToMany(Auditor::class, 'checklist_auditors', 'checklist_id', 'auditor_id');
    }

    public function auditedItems() {
        return $this->belongsToMany(AuditedItem::class, 'checklist_audited_items', 'audit_checklist_id', 'audited_item_id');
    }

    public function checklistAuditedItems() {
        return $this->hasMany(ChecklistAuditedItem::class, 'audit_checklist_id');
    }

    public function checklistAuditedItemTimestamps() {
        return $this->hasManyThrough(ChecklistAuditedItemTimestamp::class, ChecklistAuditedItem::class, 'audit_checklist_id', 'checklist_audited_item_id');
    }

    public function schemes() {
        return $this->belongsToMany(Scheme::class, AuditChecklistScheme::class);
    }

    public function auditChecklistSchemes() {
        return $this->hasMany(AuditChecklistScheme::class);
    }

    public function overallAnswers() {
        return $this->hasMany(CheckpointOverallAnswer::class, 'checklist_id');
    }

    public function multipleAnswers() {
        return $this->hasMany(CheckpointMultipleAnswer::class, 'checklist_id');
    }

    public function customFieldValues() {
        return $this->hasMany(ChecklistCustomFieldValue::class, 'checklist_id');
    }




}
