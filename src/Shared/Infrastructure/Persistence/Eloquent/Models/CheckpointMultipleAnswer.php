<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckpointMultipleAnswer extends Model
{
    use SoftDeletes;
    use UuidTrait;
    protected $guarded = [];

    public function categoricalValueOption() {
        return $this->belongsTo(CheckpointValueOption::class, 'categorical_value_option_id');
    }
}
