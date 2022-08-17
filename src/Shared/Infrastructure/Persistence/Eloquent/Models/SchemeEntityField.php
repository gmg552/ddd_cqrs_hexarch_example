<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemeEntityField extends Model
{
    protected $table = "scheme_entity_fields";
    protected $guarded = []; //Se mete para poder hacer el create []
    use softDeletes;
    use UuidTrait;

    public function entityField()
    {
        return $this->belongsTo(EntityField::class);
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class);
    }

}
