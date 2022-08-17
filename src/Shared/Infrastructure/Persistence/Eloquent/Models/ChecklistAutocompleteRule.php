<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistAutocompleteRule extends Model
{
    protected $table = "checklist_autocomplete_rules";
    use UuidTrait;
    use softDeletes;

    public function presetObservations() {
        return $this->hasMany(ChecklistPresetObservation::class, 'rule_id');
    }
}
