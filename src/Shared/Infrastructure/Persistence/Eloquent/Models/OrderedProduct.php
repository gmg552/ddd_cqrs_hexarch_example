<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderedProduct extends Model
{
    protected $table = "ordered_products";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function orderedScheme()
    {
        return $this->belongsTo(OrderedScheme::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

}
