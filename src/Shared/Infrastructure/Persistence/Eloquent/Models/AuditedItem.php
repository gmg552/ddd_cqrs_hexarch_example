<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditedItem extends EloquentEntity
{
    protected $table = "audited_items";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function realAuditor(int $checklistId){
        return Auditor::join('checklist_audited_items', 'checklist_audited_items.real_auditor_id', 'auditors.id')
            ->where('checklist_audited_items.audit_checklist_id', $checklistId)
            ->where('checklist_audited_items.audited_item_id', $this->id)
            ->select('auditors.*')
            ->first();
    }

    public function checklistCustomFieldValues() {
        return $this->hasMany(ChecklistCustomFieldValue::class);
    }

    public function checklistAuditedItems() {
        return $this->hasMany(ChecklistAuditedItem::class);
    }

    public function auditedOperator() {
        return $this->hasOne(AuditedOperator::class, 'id', 'id');
    }

    public function auditedHandlingUnit() {
        return $this->hasOne(AuditedHandlingUnit::class, 'id');
    }

    public function auditedProductionUnit() {
        return $this->hasOne(AuditedProductionUnit::class, 'id');
    }

    public function auditedSchemes() {
        return $this->belongsToMany(AuditedScheme::class, 'audited_item_schemes', 'audited_item_id', 'audited_scheme_id');
    }

    public function nonConformities() {
        return $this->belongsToMany(NonConformity::class, 'audited_item_non_conformities', 'audited_item_id', 'non_conformity_id');
    }

    public function auditedItemSchemes() {
        return $this->hasMany(AuditedItemScheme::class);
    }

    public function audit() {
        return $this->belongsTo(Audit::class);
    }

    public function siteAnswers() {
        return $this->hasMany(CheckpointSiteAnswer::class, 'audited_item_id');
    }

}
