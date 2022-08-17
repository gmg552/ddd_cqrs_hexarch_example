<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditControlLevelType extends Model
{
    protected $table = "audit_control_level_types";
    use UuidTrait;
    use softDeletes;

    public function scheme()
    {
        return $this->belongsTo('App\Models\Certification\Common\Scheme');
    }

}
