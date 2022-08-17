<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderedScheme extends Model
{
    protected $table = "ordered_schemes";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function schemeFile(){
        return $this->belongsTo('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\SchemeFile');
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'ordered_products', 'ordered_scheme_id', 'product_id')->withPivot(['parallel_production', 'parallel_ownership']);
    }

    public function schemeModes() {
        return $this->belongsToMany(SchemeMode::class, 'ordered_scheme_modes', 'ordered_scheme_id', 'scheme_mode_id');
    }

    public function advisor() {
        return $this->belongsTo(Advisor::class);
    }

    public function scheme() {
        return $this->belongsTo(Scheme::class);
    }

    public function schemeOrder() {
        return $this->belongsTo(SchemeOrder::class);
    }

    public function operator() {
        return $this->belongsTo(Operator::class);
    }

}
