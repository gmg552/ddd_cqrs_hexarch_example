<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeOwnerChecklistTemplate extends Model
{
    protected $table = "scheme_owner_checklist_templates";
    protected $guarded = []; //Se mete para poder hacer el create []
    use softDeletes;
    use UuidTrait;

    public function baseScheme() {
        return $this->belongsTo(Scheme::class, 'base_scheme_id');
    }

}
