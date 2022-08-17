<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audit extends Model
{
    protected $table = "audits";
    use softDeletes;
    use UuidTrait;

    public function massBalanceRecords() {
        return $this->hasMany(AuditMassBalanceRecord::class);
    }

    public function traceabilityRecords() {
        return $this->hasMany(AuditTraceabilityRecord::class);
    }

    public function schemeOrder() {
        return $this->belongsTo(SchemeOrder::class, 'scheme_order_id');
    }

    public function operator() {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function teamMembers() {
        return $this->belongsToMany(Auditor::class, 'audit_team_members', 'audit_id', 'auditor_id');
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'audit_products', 'audit_id', 'product_id')->withPivot(['crop_inspected', 'harvest_inspected', 'handling_inspected']);
    }

    public function realAuditor() {
        return $this->belongsTo(Auditor::class, 'real_chief_auditor_id');
    }

    public function auditType() {
        return $this->belongsTo(AuditType::class);
    }

    public function auditTeamMembers() {
        return $this->hasMany(AuditTeamMember::class);
    }

    public function operatorRepresentatives() {
        return $this->hasMany(OperatorRepresentative::class);
    }

    public function auditedItems() {
        return $this->hasMany(AuditedItem::class);
    }

    public function auditedSites() {
        return $this->hasMany(AuditedItem::class);
    }

    public function nonConformities() {
        return $this->hasManyThrough(NonConformity::class, AuditedScheme::class);
    }

    public function auditChecklists() {
        return $this->hasMany(AuditChecklist::class);
    }

    public function checklistAuditedItems() {
        return $this->hasManyThrough(ChecklistAuditedItem::class, AuditChecklist::class, 'audit_id', 'audit_checklist_id');
    }

    public function auditedSchemes() {
        return $this->hasMany(AuditedScheme::class);
    }

    public function realChiefAuditor() {
        return $this->hasOne(Auditor::class, 'id', 'real_chief_auditor_id');
    }

    public function strawChiefAuditor() {
        return $this->hasOne(Auditor::class, 'id', 'straw_chief_auditor_id');
    }

    public function caps() {
        return $this->hasMany(Cap::class);
    }

    public function auditDecisions() {
        return $this->hasMany(AuditDecision::class);
    }

    public function auditReviewItemValues() {
        return $this->hasMany(AuditReviewItemValue::class);
    }

}
