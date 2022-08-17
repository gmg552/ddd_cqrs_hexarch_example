<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    protected $table = "provinces";
    protected $guarded = [];
    use UuidTrait;
    use softDeletes;

    public function country() {
        return $this->belongsTo(Country::class);
    }

}
