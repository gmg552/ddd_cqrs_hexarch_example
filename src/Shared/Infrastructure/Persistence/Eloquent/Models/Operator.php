<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operator extends Model
{
    protected $table = "operators";
    protected $guarded = [];
    public $incrementing = false;
    use UuidTrait;
    use softDeletes;

    public function subject() {
        return $this->belongsTo(Subject::class, 'id', 'id');
    }

    public function representative() {
        return $this->belongsTo(Subject::class, 'representative_id');
    }

}
