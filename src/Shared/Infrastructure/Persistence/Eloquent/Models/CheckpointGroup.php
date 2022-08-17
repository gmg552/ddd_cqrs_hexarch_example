<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckpointGroup extends Model
{
    protected $table = "checkpoint_groups";
    use SoftDeletes;
    use UuidTrait;

    public function checkpointGroups() {
        return $this->hasMany(CheckpointGroup::class, 'parent_id');
    }

    public function checkpoints() {
        return $this->hasMany(ChecklistCheckpoint::class, 'checkpoint_group_id');
    }

    public function parentGroup() {
        return $this->belongsTo(CheckpointGroup::class, 'parent_id');
    }

    public function checklistVersion() {
        return $this->belongsTo(ChecklistVersion::class, 'checklist_version_id');
    }

}
