<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CroppedAreaIteration extends Model
{
    protected $table = "cropped_area_iterations";
    protected $guarded = [];
    public $incrementing = false;
    use softDeletes;
    use UuidTrait;

    public function iteration() {
        return $this->belongsTo(Iteration::class, 'id', 'id');
    }

}
