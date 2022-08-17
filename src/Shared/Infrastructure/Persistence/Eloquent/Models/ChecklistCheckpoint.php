<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistCheckpoint extends Model
{
    protected $table = "checklist_checkpoints";
    use softDeletes;
    use UuidTrait;

    public function checkpointGroup() {
        return $this->belongsTo(CheckpointGroup::class, 'checkpoint_group_id');
    }

    public function categoricalOptions() {
        return $this->hasMany(CheckpointValueOption::class, 'checkpoint_id');
    }

    public function categoricalOverallOptions() {
        return $this->hasMany(CheckpointValueOption::class, 'checkpoint_id')
            ->where('for_overall_context', true);
    }

    public function categoricalMultiOptions() {
        return $this->hasMany(CheckpointValueOption::class, 'checkpoint_id')
            ->where('for_multianswer_context', true);
    }

    public function categoricalSiteOptions() {
        return $this->hasMany(CheckpointValueOption::class, 'checkpoint_id')
            ->where('for_site_context', true);
    }

    public function scheme() {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }

    public function level() {
        return $this->belongsTo(ChecklistLevel::class, 'level_id');
    }

    public function overallAnswer() {
        return $this->hasOne(CheckpointOverallAnswer::class, 'checkpoint_id');
    }

    public function siteAnswers() {
        return $this->hasMany(CheckpointSiteAnswer::class, 'checkpoint_id');
    }

    public function multiAnswers() {
        return $this->hasMany(CheckpointMultipleAnswer::class, 'checkpoint_id');
    }

    public function presetObservations() {
        return $this->hasMany(ChecklistPresetObservation::class, 'checkpoint_id');
    }

    public function overallPresetObservations() {
        return $this->hasMany(ChecklistPresetObservation::class, 'checkpoint_id')
            ->where('scope', 'overall');
    }

    public function sitePresetObservations() {
        return $this->hasMany(ChecklistPresetObservation::class, 'checkpoint_id')
            ->where('scope', 'site');
    }

    public function helpLinks(){
        return $this->hasMany(CheckpointHelpLinks::class, 'checkpoint_id');
    }

    public function multianswerPresetObservations() {
        return $this->hasMany(ChecklistPresetObservation::class, 'checkpoint_id')
            ->where('scope', 'multianswer');
    }

    public function prevailingCategoricalOption() {
        return $this->belongsTo(ChecklistCategoricalValue::class, 'categorical_value_option_id');
    }

    public function checkpointTags() {
        return $this->belongsToMany(ChecklistTag::class, 'checkpoint_tags', 'checkpoint_id', 'checklist_tag_id');
    }

    public function nonConformities() {
        return $this->belongsToMany(NonConformity::class, 'checkpoint_non_conformities', 'checkpoint_id', 'non_conformity_id');
    }

}
