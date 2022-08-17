<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistVersionComplianceGrade extends Model
{
    protected $table = "checklist_version_compliance_grades";
    use softDeletes;
    use UuidTrait;

    public function checklistVersionComplianceCriterias(){
        return $this->hasMany(ChecklistVersionComplianceCriteria::class, 'checklist_version_compliance_grade_id');
    }
}
