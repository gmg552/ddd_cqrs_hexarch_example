<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckpointTag extends Model
{
    protected $table = "checkpoint_tags";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


}
