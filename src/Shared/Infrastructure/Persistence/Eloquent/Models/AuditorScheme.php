<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditorScheme extends Model
{
    protected $table = "auditor_schemes";
    use UuidTrait;
    use softDeletes;

    public function auditor()
    {
        return $this->belongsTo('App\Models\Certification\Common\Auditor');
    }

    public function scheme()
    {
        return $this->belongsTo('App\Models\Certification\Common\Scheme');
    }

}
