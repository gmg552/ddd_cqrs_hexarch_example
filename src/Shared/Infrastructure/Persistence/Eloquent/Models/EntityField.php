<?php

namespace Qalis\Shared\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntityField extends Model
{
    protected $table = "entity_fields";
    protected $guarded = []; //Se mete para poder hacer el create []
    use softDeletes;
    use UuidTrait;

    public function entity() {
        return $this->belongsTo(Entity::class);
    }

    public function schemeEntityFields() {
        return $this->hasMany(SchemeEntityField::class);
    }

}
