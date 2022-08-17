<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistLevel extends Model
{
    protected $table = "checklist_levels";
    use UuidTrait;
    use softDeletes;
}
