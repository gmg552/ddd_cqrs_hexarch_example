<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regulation extends Model
{
    protected $table = "regulations";
    use softDeletes;
    use UuidTrait;

    public function regulationSet() {
        return $this->belongsTo(RegulationSet::class, 'id', 'id');
    }

}
