<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistTag extends Model
{
    protected $table = "checklist_tags";
    use softDeletes;
    use UuidTrait;
}
