<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeRegulation extends Model
{
    protected $table = "scheme_regulations";
    use softDeletes;
    use UuidTrait;

    public function regulation() {
        return $this->belongsTo(Regulation::class, 'id', 'id');
    }

    public function scheme() {
        return $this->belongsTo(Scheme::class, 'id', 'id');
    }

    public function schemeVersion() {
        return $this->belongsTo(SchemeVersion::class, 'id', 'id');
    }

}
