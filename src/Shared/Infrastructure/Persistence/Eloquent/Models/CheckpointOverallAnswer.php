<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckpointOverallAnswer extends Model
{
    protected $table = 'checkpoint_overall_answers';
    use SoftDeletes;
    use UuidTrait;

    public function categoricalValueOption() {
        return $this->belongsTo(CheckpointValueOption::class, 'categorical_value_option_id');
    }

    public function checkpoint() {
        return $this->belongsTo(ChecklistCheckpoint::class, 'checkpoint_id');
    }

}
