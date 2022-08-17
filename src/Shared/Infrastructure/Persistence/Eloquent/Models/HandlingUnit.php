<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Certification\Common\Scheme;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\OperatorIteration;

class HandlingUnit extends Model
{
    protected $table = "handling_units";
    use softDeletes;
    use UuidTrait;

    protected $guarded = [];

    public function iteratedHandlingUnits() {
        return $this->hasMany(IteratedHandlingUnit::class);
    }

}
