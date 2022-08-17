<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistEvaluationVar extends Model
{
    protected $table = "checklist_evaluation_vars";
    use softDeletes;
    use UuidTrait;

    public function checkpointVarEvaluationRules() {
        return $this->hasMany(CheckpointVarEvaluationRule::class, 'checklist_evaluation_var_id');
    }

    public function varAssignmentEvaluationRules(){
        return $this->hasMany(VarAssignmentEvaluationRule::class, 'checklist_evaluation_var_id');
    }
}
