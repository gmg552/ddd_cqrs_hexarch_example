<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeOrder extends Model
{
    protected $table = "scheme_orders";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function privacyLevel() {
        return $this->belongsTo(PrivacyLevel::class);
    }

    public function baseScheme()
    {
        return $this->belongsTo(Scheme::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'holder_id');
    }

    public function orderedSchemes(){
        return $this->hasMany(OrderedScheme::class, 'scheme_order_id');
    }

    public function offer() {
        return $this->hasOne(Offer::class, 'id', 'offer_id');
    }

}
