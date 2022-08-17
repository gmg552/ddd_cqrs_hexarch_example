<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IteratedCrop extends Model
{
    protected $table = "iterated_crops";
    use softDeletes;
    use UuidTrait;


    public function iteration(){
        return $this->belongsTo(CropIteration::class, 'iteration_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }


}
