<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeProduct extends Model
{
    protected $table = "scheme_products";
    use softDeletes;
    use UuidTrait;

    public function product()
    {
        return $this->belongsTo('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Product');
    }

    public function scheme()
    {
        return $this->belongsTo('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Scheme');
    }

}
