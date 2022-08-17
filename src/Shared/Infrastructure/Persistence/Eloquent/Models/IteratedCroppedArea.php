<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Certification\Common\Scheme;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\OperatorIteration;

class IteratedCroppedArea extends Model
{
    protected $table = "iterated_cropped_areas";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


    public function iteration(){
        return $this->belongsTo(CroppedAreaIteration::class, 'iteration_id');
    }

    public function croppedArea(){
        return $this->belongsTo(CroppedArea::class, 'cropped_area_id');
    }

}
