<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckpointVarEvaluationRule extends Model
{
    protected $table = "checkpoint_var_evaluation_rules";
    use softDeletes;
    use UuidTrait;

    public function checklistEvaluationVar(){
        return $this->belongsTo(ChecklistEvaluationVar::class, 'checklist_evaluation_var_id');
    }
}
