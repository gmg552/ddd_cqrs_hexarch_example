<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Product;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\IteratedHandlingUnit;

class IteratedHandledProduct extends Model
{
    protected $table = "iterated_handled_products";
    use softDeletes;
    use UuidTrait;


    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function iteratedHandlingUnit(){
        return $this->belongsTo(IteratedHandlingUnit::class);
    }

}
