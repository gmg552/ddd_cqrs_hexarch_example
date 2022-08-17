<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeVersion extends Model
{
    protected $table = "scheme_versions";
    protected $guarded = [];
    use softDeletes;
    use UuidTrait;

    public function scheme()
    {
        return $this->belongsTo('Qalis\Shared\Infrastructure\Persistence\Eloquent\Models\Scheme');
    }

}
