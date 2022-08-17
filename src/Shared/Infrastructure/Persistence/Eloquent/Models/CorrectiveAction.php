<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorrectiveAction extends Model
{
    protected $table = "corrective_actions";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

}
