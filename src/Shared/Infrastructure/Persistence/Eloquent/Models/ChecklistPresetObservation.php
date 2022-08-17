<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistPresetObservation extends Model
{
    protected $table = "checklist_preset_observations";
    use softDeletes;
    use UuidTrait;


    public function categoricalValue() {
        return $this->belongsTo(ChecklistCategoricalValue::class, 'categorical_value_id');
    }

    public function checkpoint() {
        return $this->belongsTo(ChecklistCheckpoint::class, 'checkpoint_id');
    }

    public function rule(){
        return $this->belongsTo(ChecklistAutocompleteRule::class, 'rule_id');
    }

    public function auditor(){
        return $this->belongsTo(Auditor::class, 'auditor_id');
    }
}
