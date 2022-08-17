<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VarAssignmentEvaluationRule extends Model
{
    protected $table = "var_assignment_evaluation_rules";
    use softDeletes;
    use UuidTrait;


    public function checklistEvaluationVar(){
        return $this->belongsTo(ChecklistEvaluationVar::class, 'checklist_evaluation_var_id');
    }
}
