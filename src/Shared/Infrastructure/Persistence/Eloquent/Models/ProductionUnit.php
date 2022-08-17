<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionUnit extends Model
{
    protected $table = "production_units";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function croppedAreas() {
        return $this->hasMany(CroppedArea::class);
    }

}
