<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Certification\Common\Scheme;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\OperatorIteration;

class IteratedProductionUnit extends Model
{
    protected $table = "iterated_production_units";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function productionUnit() {
        return $this->belongsTo(ProductionUnit::class);
    }

    public function croppedAreaIteration() {
        return $this->belongsTo(CroppedAreaIteration::class, 'iteration_id');
    }

}
