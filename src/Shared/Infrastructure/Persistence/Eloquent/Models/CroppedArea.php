<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Certification\Common\Scheme;
use App\Models\Common\City;
use Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\OrderedScheme;

class CroppedArea extends Model
{
    protected $table = "cropped_areas";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;


    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function operator(){
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function city(){
        return $this->belongsTo(City::class, 'city_id');
    }

    public function iteratedCroppedAreas() {
        return $this->hasMany(IteratedCroppedArea::class);
    }

}
