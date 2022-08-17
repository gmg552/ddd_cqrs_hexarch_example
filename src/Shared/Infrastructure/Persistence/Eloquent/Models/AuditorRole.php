<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use App\Models\Certification\Common\Scheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditorRole extends Model
{
    protected $table = "auditor_roles";
    protected $guarded = []; //Se mete para poder hacer el create []
    use softDeletes;
    use UuidTrait;

    public function scheme() {
        return $this->belongsTo(Scheme::class, 'scheme_id');
    }

}
