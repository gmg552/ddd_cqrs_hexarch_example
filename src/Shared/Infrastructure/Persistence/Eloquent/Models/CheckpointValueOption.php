<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckpointValueOption extends Model
{
    protected $table = "checkpoint_value_options";
    use softDeletes;
    use UuidTrait;

    public function categoricalValue() {
        return $this->belongsTo(ChecklistCategoricalValue::class, 'categorical_value_id');
    }
}
