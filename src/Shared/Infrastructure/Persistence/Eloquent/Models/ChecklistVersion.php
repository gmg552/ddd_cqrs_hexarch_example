<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistVersion extends Model
{
    protected $table = "checklist_versions";
    use softDeletes;
    use UuidTrait;

    public function template() {
        return $this->belongsTo(ChecklistTemplate::class, 'template_id');
    }

    public function checkpointGroups() {
        return $this
            ->hasMany(CheckpointGroup::class, 'checklist_version_id')
            ->orderBy('order', 'asc');
    }

    public function checkpointGroupsOrderByParentID() {
        return $this
            ->hasMany(CheckpointGroup::class, 'checklist_version_id')
            ->orderBy('parent_id', 'asc');
    }

    public function levels() {
        return $this->hasMany(ChecklistLevel::class, 'checklist_version_id');
    }

    public function tags() {
        return $this->hasMany(ChecklistTag::class, 'checklist_version_id');
    }

    public function categoricalValues() {
        return $this->hasMany(ChecklistCategoricalValue::class, 'checklist_version_id');
    }

    public function checkpoints() {
        return $this->hasManyThrough(
            ChecklistCheckpoint::class,
            CheckpointGroup::class,
            'checklist_version_id',
            'checkpoint_group_id');
    }

    public function checkpointAnswerValidations() {
        return $this->hasMany(CheckpointAnswerValidation::class, 'checklist_version_id');
    }

    public function auditCheckLists() {
        return $this->hasMany(AuditChecklist::class, 'checklist_version_id');
    }

    public function checklistEvaluationVars(){
        return $this->hasMany(ChecklistEvaluationVar::class, 'checklist_version_id');
    }

    public function customFields() {
        return $this->hasMany(ChecklistCustomField::class, 'checklist_version_id');
    }

    public function checklistCustomFields() {
        return $this->hasMany(ChecklistCustomField::class, 'checklist_version_id')->where('context', 'checklist');
    }

    public function siteCustomFields() {
        return $this->hasMany(ChecklistCustomField::class, 'checklist_version_id')->where('context', 'site');
    }

    public function autocompleteRules() {
        return $this->hasMany(ChecklistAutocompleteRule::class, 'checklist_version_id');
    }

    public function complianceGrades() {
        return $this->hasMany(ChecklistVersionComplianceGrade::class, 'checklist_version_id');
    }

    public function complianceCriterias() {
        return $this->hasMany(ChecklistVersionComplianceCriteria::class, 'checklist_version_id');
    }

}
