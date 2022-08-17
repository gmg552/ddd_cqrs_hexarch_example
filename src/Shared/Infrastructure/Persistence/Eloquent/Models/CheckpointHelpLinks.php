<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckpointHelpLinks extends Model
{
    protected $table = "checkpoint_help_links";
    use softDeletes;
    use UuidTrait;


    public function checkpoint() {
        return $this->belongsTo(ChecklistCheckpoint::class, 'checkpoint_id');
    }
}
