<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Certification\Common\Scheme;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\OperatorIteration;

class IteratedHandlingUnit extends Model
{
    protected $table = "iterated_handling_units";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


    public function handlingUnitIteration(){
        return $this->belongsTo(HandlingUnitIteration::class, 'iteration_id');
    }

    public function handlingUnit(){
        return $this->belongsTo(HandlingUnit::class);
    }

    public function iteratedHandledProducts(){
        return $this->hasMany(IteratedHandledProduct::class);
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'iterated_handled_products', 'iterated_handling_unit_id', 'product_id');
    }

}
