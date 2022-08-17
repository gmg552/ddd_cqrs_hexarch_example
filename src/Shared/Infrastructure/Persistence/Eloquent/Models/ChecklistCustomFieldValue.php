<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ChecklistCustomFieldValue extends Model
{
    protected $table = "checklist_custom_field_values";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function customField() {
        return $this->belongsTo(ChecklistCustomField::class, 'checklist_custom_field_id');
    }

    public function checklist() {
        return $this->belongsTo(AuditChecklist::class, 'audit_checklists');
    }

    public function auditedSite() {
        return $this->belongsTo(AuditedSite::class, 'audited_item_id');
    }
}
