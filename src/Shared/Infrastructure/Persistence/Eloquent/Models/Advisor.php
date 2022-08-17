<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advisor extends Model
{
    protected $table = "advisors";
    protected $guarded = [];
    public $incrementing = false;
    use softDeletes;
    use UuidTrait;

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'id', 'id');
    }

}
